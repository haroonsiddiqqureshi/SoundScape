import json
import requests
import os

def seed_artists():
    db_path = os.path.join(os.path.dirname(__file__), '..', 'artists_database.json')
    
    if not os.path.exists(db_path):
        print("❌ ไม่พบไฟล์ artists_database.json! กรุณารันสคริปต์ดึงข้อมูลก่อน")
        return

    with open(db_path, 'r', encoding='utf-8') as f:
        artists = json.load(f)

    print(f"🚀 พบข้อมูลศิลปินทั้งหมด {len(artists)} รายการ กำลังส่งไปยังฐานข้อมูล Laravel...")

    api_url = "http://127.0.0.1:8000/api/artists"
    success_count = 0
    
    for i, artist in enumerate(artists, start=1):
        
        picture_url = artist["image_url"]
        if picture_url in ["No image found", "Pending (Quota Limit)"]:
            picture_url = None
            
        payload = {
            "name": artist["name"],
            "picture_url": picture_url
        }
        
        try:
            res = requests.post(api_url, json=payload, timeout=10)
            
            if res.status_code in [200, 201]:
                success_count += 1
                print(f"[{i}/{len(artists)}] ✅ ซิงค์สำเร็จ: {artist['name']}")
            else:
                print(f"[{i}/{len(artists)}] ⚠️ ล้มเหลว: {artist['name']} - {res.text}")
                
        except Exception as e:
            print(f"[{i}/{len(artists)}] ❌ เกิดข้อผิดพลาดในการเชื่อมต่อ API: {e}")
            
    print(f"\n🎉 เสร็จสิ้น! ซิงค์ข้อมูลศิลปินสำเร็จ {success_count} / {len(artists)} รายการไปยัง Laravel")

if __name__ == "__main__":
    seed_artists()