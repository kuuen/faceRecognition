import time
from selenium import webdriver

driver = webdriver.Chrome() # WebDriverのインスタンスを作成
# driver.get('https://www.yahoo.co.jp/') # URLを指定してブラウザを開く

driver.get('https://www.amazon.co.jp/gp/help/seller/at-a-glance.html/ref=dp_merchant_link?ie=UTF8&seller=A3ANTTIDFSTRNP&isAmazonFulfilled=1')
time.sleep(2) # 2秒待機
search_box = driver.find_element_by_name('p') # name属性で検索ボックスを特定
search_box.send_keys('スクレイピング') # 検索ボックスにテキストを入力
search_box.submit() # 検索文言の送信（検索ボタンを押すのと同じ）
time.sleep(2) # 2秒待機
driver.quit() # ブラウザを閉じる