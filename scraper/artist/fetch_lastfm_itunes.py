import requests
import json
import time
import os
import re
from tqdm import tqdm
from dotenv import load_dotenv

load_dotenv()
LASTFM_API_KEY = os.getenv('LASTFM_API_KEY')

# Last.fm Settings
TAGS_TO_SEARCH = ["thai", "thai pop", "thai indie", "t-pop", "thai rock", "thai alternative", "thai rap", "thai r&b", "thai hip hop", "thai hip-hop"]
ARTISTS_PER_TAG = 1000 

def is_english_only(text):
    return text.isascii()

def fetch_lastfm_artists():
    print("\n🎵 Step 1: Discovering artists on Last.fm...")
    unique_artists = {} 
    
    pbar_lastfm = tqdm(TAGS_TO_SEARCH, unit="tag")
    
    for tag in pbar_lastfm:
        pbar_lastfm.set_description(f"Scraping: {tag: <16}")
        
        url = f"http://ws.audioscrobbler.com/2.0/?method=tag.gettopartists&tag={tag}&api_key={LASTFM_API_KEY}&format=json&limit={ARTISTS_PER_TAG}"
        
        try:
            response = requests.get(url)
            response.raise_for_status()
            data = response.json()
            
            artists = data.get('topartists', {}).get('artist', [])
            
            for artist in artists:
                name = artist.get('name')
                
                if name and name not in unique_artists:
                    if not is_english_only(name):
                        continue
                        
                    unique_artists[name] = {"name": name}
                    
        except Exception as e:
            tqdm.write(f"⚠️ Error fetching tag '{tag}': {e}")
            
        time.sleep(1) 
        
    artist_list = list(unique_artists.values())
    artist_list.sort(key=lambda x: x['name'].lower())
    
    tqdm.write(f"✅ Found {len(artist_list)} unique artists (English only)!")
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
        api_response.raise_for_status()
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
                    
        return None
        
    except Exception as e:
        tqdm.write(f"⚠️ iTunes API Error for {artist_name}: {e}")
        return None

def process_itunes_images(artist_list):
    print(f"\n📸 Step 2: Fetching profile pictures from Apple Music...")
    
    final_database = []
    pbar_itunes = tqdm(artist_list, unit="artist")
    
    for artist_dict in pbar_itunes:
        name = artist_dict['name']
        
        display_name = (name[:20] + '...') if len(name) > 20 else name
        pbar_itunes.set_description(f"Searching: {display_name: <23}")
        
        image_url = get_itunes_image(name)
        
        final_database.append({
            "name": name,
            "image_url": image_url if image_url else "No image found"
        })
        
        time.sleep(1.5)
        
    return final_database

def main():
    base_artists = fetch_lastfm_artists()
    
    final_data = process_itunes_images(base_artists)
    
    filename = "artists_database.json"
    print(f"\n💾 Step 3: Saving all data to '{filename}'...")
    with open(filename, "w", encoding="utf-8") as f:
        json.dump(final_data, f, ensure_ascii=False, indent=4)
        
    print("🎉 Pipeline complete! You can open the JSON file to see your images.")

if __name__ == "__main__":
    main()