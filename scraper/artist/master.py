import sys
import os
import json
import requests
import math
import time
import urllib.parse

current_dir = os.path.dirname(os.path.abspath(__file__))
parent_dir = os.path.dirname(current_dir)
sys.path.append(parent_dir)

def update_laravel(job_id, status=None, progress=None, new_result=None, error_message=None):
    url = f"http://127.0.0.1:8000/api/scraper/update/{job_id}"
    data = {}
    if status: data["status"] = status
    if progress is not None: data["progress"] = progress
    if new_result: data["new_result"] = new_result
    if error_message: data["error_message"] = error_message
    
    try:
        response = requests.post(url, json=data, timeout=5)
        response.raise_for_status()
    except Exception:
        pass

def save_artist(data):
    url = "http://127.0.0.1:8000/api/artists"
    try:
        requests.post(url, json=data, timeout=5)
    except Exception:
        pass

def fetch_from_itunes(artist_name, existing_image=None):
    url = f"https://itunes.apple.com/search?term={urllib.parse.quote(artist_name)}&entity=song&limit=1"
    
    artist_data = {
        "name": artist_name,
        "genre": "Thai Pop",
        "picture_url": existing_image,
        "apple_music_url": None
    }

    try:
        res = requests.get(url, timeout=5).json()
        if res['resultCount'] > 0:
            track = res['results'][0]
            artist_data["name"] = track.get("artistName", artist_name)
            artist_data["genre"] = track.get("primaryGenreName", "Thai Pop")
            artist_data["apple_music_url"] = track.get("artistViewUrl")
            
            if not artist_data["picture_url"] and "artworkUrl100" in track:
                high_res_img = track["artworkUrl100"].replace("100x100bb", "1000x1000bb")
                artist_data["picture_url"] = high_res_img
                
        return artist_data
    except Exception:
        pass
        
    return artist_data

def start_scraping(job_id, file_path):
    try:
        update_laravel(job_id, status='running', progress=5)
        
        artists_to_scrape = [] 

        clean_path = file_path.strip('"\'') if file_path else "none"
        if clean_path != "none":
            clean_path = os.path.normpath(clean_path)


        if clean_path != "none" and os.path.exists(clean_path):
            print(f"📂 Reading artists from file: {clean_path}")
            with open(clean_path, 'r', encoding='utf-8') as f:
                data = json.load(f)
                for item in data:
                    if isinstance(item, str):
                        artists_to_scrape.append({"name": item, "image": None})
                    elif isinstance(item, dict) and "name" in item:
                        artists_to_scrape.append({
                            "name": item["name"], 
                            "image": item.get("image_url") 
                        })

        total = len(artists_to_scrape)
        if total == 0:
            update_laravel(job_id, status='failed', error_message="ไม่พบรายชื่อศิลปิน")
            return

        for i, artist_info in enumerate(artists_to_scrape):
            current_progress = 5 + math.floor(((i + 1) / total) * 95)
            
            name = artist_info["name"]
            existing_image = artist_info["image"]
            
            artist_data = fetch_from_itunes(name, existing_image)
            
            if artist_data:
                save_artist(artist_data)
                update_laravel(job_id, progress=current_progress, new_result=artist_data['name'])
            
            time.sleep(0.5)

        update_laravel(job_id, status='completed', progress=100)

    except Exception as e:
        update_laravel(job_id, status='failed', error_message=str(e))