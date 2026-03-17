import sys
import os
import json
import requests
import shutil

current_dir = os.path.dirname(os.path.abspath(__file__))
parent_dir = os.path.dirname(current_dir)
sys.path.append(parent_dir)

from .fetch_lastfm_itunes import fetch_lastfm_artists, process_itunes_images
from .seed_artists import seed_artists

def update_laravel(job_id, status=None, progress=None, new_result=None, error_message=None):
    url = f"http://127.0.0.1:8000/api/scraper/update/{job_id}"
    data = {}
    if status: data["status"] = status
    if progress is not None: data["progress"] = progress
    if new_result: data["new_result"] = new_result
    if error_message: data["error_message"] = error_message
    
    try:
        requests.post(url, json=data, timeout=5)
    except Exception:
        pass

def start_scraping(job_id, file_path):
    try:
        update_laravel(job_id, status='running', progress=5, new_result="กำลังเตรียมระบบ...")
        
        clean_path = file_path.strip('"\'') if file_path else "none"
        if clean_path != "none":
            clean_path = os.path.normpath(clean_path)

        db_path = os.path.join(current_dir, '..', 'artists_database.json')

        if clean_path != "none" and os.path.exists(clean_path):
            update_laravel(job_id, progress=20, new_result=f"อ่านไฟล์ที่อัปโหลดมา...")
            shutil.copy(clean_path, db_path)
            
        else:
            update_laravel(job_id, progress=10, new_result="กำลังค้นหาศิลปินจาก Last.fm...")
            base_artists = fetch_lastfm_artists()
            
            update_laravel(job_id, progress=40, new_result="กำลังดึงรูปจาก Apple Music... (ขั้นตอนนี้อาจใช้เวลาหลายนาที)")
            final_data = process_itunes_images(base_artists)
            
            with open(db_path, "w", encoding="utf-8") as f:
                json.dump(final_data, f, ensure_ascii=False, indent=4)

        update_laravel(job_id, progress=80, new_result="กำลังส่งข้อมูลทั้งหมดเข้าสู่ฐานข้อมูล...")
        seed_artists(job_id)

        update_laravel(job_id, status='completed', progress=100, new_result="อัปเดตข้อมูลศิลปินเรียบร้อยแล้ว!")

    except Exception as e:
        update_laravel(job_id, status='failed', error_message=str(e))