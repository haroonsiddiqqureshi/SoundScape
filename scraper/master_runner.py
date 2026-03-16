import sys
import os
import traceback

print("🚀 Master Runner Started!")

current_dir = os.path.dirname(os.path.abspath(__file__))
sys.path.append(current_dir)

target_type = sys.argv[1]    
target_website = sys.argv[2] 
job_id = sys.argv[3]         
file_path = sys.argv[4]      

print(f"👉 Target: {target_type} | Website: {target_website} | Job ID: {job_id}")

try:
    if target_type == 'concert':
        print(f"Attempting to import {target_website} scraper...")
        print(f"Attempting to import {target_website} scraper...")
        if target_website == 'ticketier':
            from ticketier.master import start_scraping
        elif target_website == 'allticket':
            from allticket.master import start_scraping
        elif target_website == 'theconcert':
            from theconcert.master import start_scraping
        elif target_website == 'highlight':
            from highlight.master import start_scraping
        else:
            raise ValueError(f"Unknown website: {target_website}")
            
        print("✅ Import successful! Triggering start_scraping()...")
        start_scraping(job_id)
        
    elif target_type == 'artist':
        print("Attempting to import artist scraper...")
        
        from artist.master import start_scraping
        print("✅ Import successful! Triggering start_scraping()...")
        
        start_scraping(job_id, file_path)

except Exception as e:
    print(f"\n❌ FATAL PYTHON ERROR: {e}")
    traceback.print_exc()
    
    try:
        import requests
        url = f"http://127.0.0.1:8000/api/scraper/update/{job_id}"
        requests.post(url, json={"status": "failed", "error_message": str(e)}, timeout=5)
    except Exception as api_error:
        print(f"⚠️ Failed to notify Laravel: {api_error}")