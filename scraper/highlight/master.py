import time
import urllib.parse
import requests
import sys
import os
from selenium import webdriver
from selenium.webdriver.edge.service import Service
from selenium.webdriver.edge.options import Options
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

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
    except Exception as e:
        pass

def get_highlights(url, job_id):
    highlights = []
    seen_links = set() 
    
    edge_options = Options()
    edge_options.add_argument("--headless=new")
    edge_options.add_argument("--window-size=1920,1080")
    user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36"
    edge_options.add_argument(f'user-agent={user_agent}')
    edge_options.add_argument("--disable-blink-features=AutomationControlled")
    edge_options.add_experimental_option("excludeSwitches", ["enable-automation"])
    edge_options.add_experimental_option('useAutomationExtension', False)
    edge_options.add_argument("--log-level=3")
    
    driver_path = os.path.join(parent_dir, "msedgedriver.exe")
    service = Service(executable_path=driver_path)
    driver = webdriver.Edge(service=service, options=edge_options)
    
    try:
        driver.get(url)
        
        WebDriverWait(driver, 15).until(
            EC.presence_of_element_located((By.CSS_SELECTOR, "li.slide"))
        )
        time.sleep(2)
        
        slides = driver.find_elements(By.CSS_SELECTOR, "li.slide")
        
        total_slides = len(slides)
        
        for i, slide in enumerate(slides):
            try:
                a_tag = slide.find_element(By.TAG_NAME, "a")
                link = a_tag.get_attribute("href")
                
                if not link or "/events/" not in link:
                    continue
                if link in seen_links:
                    continue
                seen_links.add(link)
                
                img_tag = slide.find_element(By.TAG_NAME, "img")
                img_src = img_tag.get_attribute("src")
                
                actual_img_url = img_src
                if img_src and "/_next/image" in img_src:
                    parsed_url = urllib.parse.urlparse(img_src)
                    query_params = urllib.parse.parse_qs(parsed_url.query)
                    if 'url' in query_params:
                        actual_img_url = query_params['url'][0]
                
                if link.startswith('/'):
                    full_link = f"https://www.ticketier.com{link}"
                else:
                    full_link = link
                    
                title = full_link.strip('/').split('/')[-1]
                        
                highlights.append({
                    'title': title,
                    'link': full_link,
                    'picture_url': actual_img_url
                })
                
                current_progress = 10 + int(((i + 1) / total_slides) * 40)
                update_laravel(job_id, progress=current_progress, new_result=f"{title}")
                
            except Exception as e:
                continue

    except Exception as e:
        raise Exception(f"Failed to extract highlights: {e}")
    finally:
        driver.quit()
        
    return highlights

def sync_highlights_to_db(highlights_data, job_id):
    url = "http://127.0.0.1:8000/api/highlights/sync"
    
    try:
        update_laravel(job_id, progress=75)
        res = requests.post(url, json={"highlights": highlights_data}, timeout=15)
        
        if res.status_code == 200:
            update_laravel(job_id, progress=90)
        else:
            raise Exception(f"Sync Failed. Status Code {res.status_code}: {res.text}")
    except Exception as e:
        raise Exception(f"Database Sync Error: {e}")

def start_scraping(job_id):
    HOME_PAGE_URL = "https://www.ticketier.com/home"
    
    try:
        update_laravel(job_id, status="running", progress=10)
        
        results = get_highlights(HOME_PAGE_URL, job_id)
        
        if len(results) > 0:
            sync_highlights_to_db(results, job_id)
        else:
            update_laravel(job_id, status="failed", error_message="No highlights found on the page.")
            return
            
        update_laravel(job_id, status="completed", progress=100)
        
    except Exception as e:
        update_laravel(job_id, status="failed", error_message=str(e))