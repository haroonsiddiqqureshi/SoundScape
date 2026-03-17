from selenium import webdriver
from selenium.webdriver.edge.service import Service
from selenium.webdriver.edge.options import Options
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time
import requests
import sys
import os
import math

current_dir = os.path.dirname(os.path.abspath(__file__))
parent_dir = os.path.dirname(current_dir)
sys.path.append(parent_dir)

from urllib.parse import urljoin
from utils.artist_matcher import extract_artists_from_text
from .tester import get_page_destination_data, save_concert

def update_laravel(job_id, status=None, progress=None, new_result=None, error_message=None):
    try:
        current_file_path = os.path.abspath(__file__)
        base_dir = os.path.dirname(os.path.dirname(os.path.dirname(current_file_path)))
        kill_file = os.path.join(base_dir, 'storage', 'logs', f'cancel_{job_id}.txt')
        
        if os.path.exists(kill_file):
            try:
                os.remove(kill_file) 
            except Exception:
                pass
            os._exit(1)
    except Exception:
        pass
        
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
        print(f"⚠️ API Update Failed: {e}")

def get_all_concert_links(listing_url):
    links = []
    edge_options = Options()
    edge_options.add_argument("--headless=new")
    edge_options.add_argument("--window-size=1920,1080")
    
    edge_options.binary_location = r"C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"
    
    edge_options.add_argument("--log-level=3")
    edge_options.add_experimental_option("excludeSwitches", ["enable-logging"])

    driver_path = os.path.join(parent_dir, "msedgedriver.exe")
    service = Service(executable_path=driver_path)

    driver = webdriver.Edge(service=service, options=edge_options)

    try:
        driver.get(listing_url)
        last_height = driver.execute_script("return document.body.scrollHeight")
        while True:
            driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
            time.sleep(2)
            new_height = driver.execute_script("return document.body.scrollHeight")
            if new_height == last_height: break
            last_height = new_height

        target_selector = "div.concert-list a"
        try:
            WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.CSS_SELECTOR, target_selector)))
            a_elements = driver.find_elements(By.CSS_SELECTOR, target_selector)
            for a in a_elements:
                try:
                    href = a.get_attribute("href")
                    if href and "/concert/" in href and "login" not in href:
                        full_url = urljoin(listing_url, href)
                        if full_url not in links: links.append(full_url)
                except: continue
        except: pass
    except: pass
    finally: driver.quit()
    return links

def trigger_cleanup(origin_name):
    url = "http://127.0.0.1:8000/api/concerts/cleanup"
    try:
        requests.post(url, json={"origin": origin_name}, timeout=10)
    except: pass

def start_scraping(job_id):
    MAIN_PAGE_URL = "https://www.theconcert.com/concert"
    ORIGIN_NAME = "The Concert"
    try:
        update_laravel(job_id, status="running", progress=5)
        concert_urls = get_all_concert_links(MAIN_PAGE_URL)
        total = len(concert_urls)

        if total == 0:
            update_laravel(job_id, status="failed", error_message="No URLs found.")
            return

        for i, url in enumerate(concert_urls):
            current_progress = 5 + math.floor(((i + 1) / total) * 90)
            try:
                concert_data = get_page_destination_data(url, headless=True, timeout=20)
                if concert_data:
                    title = concert_data.get("name", "Unknown")
                    full_text = f"{title} {concert_data.get('description', '')}"
                    concert_data["artists"] = extract_artists_from_text(full_text)
                    save_concert(concert_data)
                    update_laravel(job_id, progress=current_progress, new_result=title)
            except Exception as e:
                print(f"❌ Error processing {url}: {e}")

        trigger_cleanup(ORIGIN_NAME)
        update_laravel(job_id, status="completed", progress=100)
    except Exception as e:
        update_laravel(job_id, status="failed", error_message=str(e))