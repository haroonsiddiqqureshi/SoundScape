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

from utils.artist_matcher import extract_artists_from_text
from .tester import get_page_destination_data, save_concert

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

def get_all_concert_links(listing_url):
    links = []
    edge_options = Options()
    edge_options.add_argument("--headless=new")
    edge_options.add_argument("--window-size=1920,1080")
    user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36 Edg/120.0.0.0"
    edge_options.add_argument(f"user-agent={user_agent}")
    edge_options.add_argument("--disable-gpu")
    edge_options.add_argument("--disable-blink-features=AutomationControlled")
    edge_options.add_experimental_option("excludeSwitches", ["enable-automation"])

    driver_path = os.path.join(parent_dir, "msedgedriver.exe")
    service = Service(executable_path=driver_path)

    driver = webdriver.Edge(service=service, options=edge_options)

    try:
        driver.get(listing_url)
        time.sleep(3)
        try:
            WebDriverWait(driver, 15).until(EC.presence_of_element_located((By.CSS_SELECTOR, ".btn-buy-now")))
        except: return []

        buttons = driver.find_elements(By.CSS_SELECTOR, ".ticket .btn")
        total_events = len(buttons)

        for i in range(total_events):
            try:
                WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.CSS_SELECTOR, ".btn-buy-now")))
                current_buttons = driver.find_elements(By.CSS_SELECTOR, ".btn-buy-now")
                if i >= len(current_buttons): continue
                target_button = current_buttons[i]
                driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", target_button)
                time.sleep(1)
                driver.execute_script("arguments[0].click();", target_button)
                WebDriverWait(driver, 10).until(lambda d: "/event/" in d.current_url)
                
                if driver.current_url not in links: links.append(driver.current_url)
                driver.back()
                time.sleep(3)
            except:
                try:
                    if "category/concert" not in driver.current_url:
                        driver.back()
                        time.sleep(3)
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
    MAIN_PAGE_URL = "https://www.allticket.com/category/concert"
    ORIGIN_NAME = "All Ticket"
    try:
        update_laravel(job_id, status='running', progress=5)
        concert_urls = get_all_concert_links(MAIN_PAGE_URL)
        total = len(concert_urls)

        if total == 0:
            update_laravel(job_id, status='failed', error_message="No URLs found.")
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
                pass

        trigger_cleanup(ORIGIN_NAME)
        update_laravel(job_id, status='completed', progress=100)
    except Exception as e:
        update_laravel(job_id, status='failed', error_message=str(e))