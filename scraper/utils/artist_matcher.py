import json
import os
import re

db_path = os.path.join(os.path.dirname(__file__), '..', 'artists_database.json')
KNOWN_ARTISTS = []

if os.path.exists(db_path):
    with open(db_path, 'r', encoding='utf-8') as f:
        KNOWN_ARTISTS = json.load(f)

def extract_artists_from_text(text):
    if not text:
        return []

    found_artists = []
    text_lower = text.lower()
    
    for artist in KNOWN_ARTISTS:
        pattern = r'\b' + re.escape(artist['name'].lower()) + r'\b'
        
        if re.search(pattern, text_lower):
            found_artists.append({
                "name": artist['name'],
                "image_url": artist['image_url'],
            })
            
    return found_artists