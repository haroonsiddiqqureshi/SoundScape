import requests
import json
import time
import os
import re
from dotenv import load_dotenv

load_dotenv()
LASTFM_API_KEY = os.getenv('LASTFM_API_KEY')

TAGS_TO_SEARCH = ["thai", "thai pop", "thai indie", "t-pop", "thai rock", "thai alternative", "thai rap", "thai r&b", "thai hip hop", "thai hip-hop"]
ARTISTS_PER_TAG = 1000 

def is_english_only(text):
    return text.isascii()

def fetch_lastfm_artists():
    unique_artists = {} 
    
    for tag in TAGS_TO_SEARCH:
        url = f"http://ws.audioscrobbler.com/2.0/?method=tag.gettopartists&tag={tag}&api_key={LASTFM_API_KEY}&format=json&limit={ARTISTS_PER_TAG}"
        
        try:
            response = requests.get(url)
            if response.status_code == 200:
                data = response.json()
                artists = data.get('topartists', {}).get('artist', [])
                
                for artist in artists:
                    name = artist.get('name')
                    if name and name not in unique_artists:
                        if is_english_only(name):
                            unique_artists[name] = {"name": name}
        except Exception:
            pass
            
        time.sleep(1) 
        
    artist_list = list(unique_artists.values())
    artist_list.sort(key=lambda x: x['name'].lower())
    
    return artist_list

def get_itunes_image(artist_name):
    search_url = "https://itunes.apple.com/search"
    params = {
        "term": artist_name,
        "entity": "musicArtist",
        "limit": 1,
        "country": "TH" 
    }
    
    try:
        api_response = requests.get(search_url, params=params)
        if api_response.status_code == 200:
            data = api_response.json()
            if data.get('resultCount', 0) > 0:
                artist_url = data['results'][0].get('artistLinkUrl')
                
                if artist_url:
                    page_response = requests.get(artist_url)
                    match = re.search(r'<meta property="og:image" content="([^"]+)"', page_response.text)
                    if match:
                        raw_url = match.group(1)
                        square_url = re.sub(r'\d+x\d+[a-zA-Z]*\.(jpg|png|webp)', '800x800cc.jpg', raw_url)
                        return square_url
    except Exception:
        pass
        
    return None

def process_itunes_images(artist_list):
    final_database = []
    
    for artist_dict in artist_list:
        name = artist_dict['name']
        image_url = get_itunes_image(name)
        
        final_database.append({
            "name": name,
            "image_url": image_url if image_url else "No image found"
        })
        
        time.sleep(1.5)
        
    return final_database