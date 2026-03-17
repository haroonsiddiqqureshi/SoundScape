import json
import requests
import os
from concurrent.futures import ThreadPoolExecutor

def seed_artists(job_id=None):
    current_dir = os.path.dirname(__file__)
    db_path = os.path.join(current_dir, '..', 'artists_database.json')
    
    if not os.path.exists(db_path):
        return

    with open(db_path, 'r', encoding='utf-8') as f:
        artists = json.load(f)

    api_url = "http://127.0.0.1:8000/api/artists"
    session = requests.Session()
    
    def push_to_db(artist):
        if job_id:
            kill_file = os.path.join(current_dir, '..', '..', 'storage', 'logs', f'cancel_{job_id}.txt')
            if os.path.exists(kill_file):
                try:
                    os.remove(kill_file)
                except Exception:
                    pass
                os._exit(1)

        picture_url = artist.get("image_url")
        if picture_url in ["No image found", "Pending (Quota Limit)"]:
            picture_url = None
            
        payload = {
            "name": artist["name"],
            "picture_url": picture_url
        }
        
        try:
            res = session.post(api_url, json=payload, timeout=5)
            if res.status_code in [200, 201]:
                return True
            return False
        except Exception:
            return False

    with ThreadPoolExecutor(max_workers=3) as executor:
        executor.map(push_to_db, artists)

if __name__ == "__main__":
    seed_artists()