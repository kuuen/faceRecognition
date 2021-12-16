import time
from selenium import webdriver

# pip install selenium
# https://sites.google.com/chromium.org/driver/downloads?authuser=0 にて使用しているchromeのバージョン
# のwebdriverをダウンロードする
# ダウンロードしたexeファイルをpython.exeと同じディレクトリに保存する（場所は環境変数で確認できる）

def get(url) :
  driver.get(url)
  time.sleep(2) # 2秒待機

driver = webdriver.Chrome() # WebDriverのインスタンスを作成
# driver.get('https://www.yahoo.co.jp/') # URLを指定してブラウザを開く

def gyousya(url) :
  get('https://www.amazon.co.jp/gp/help/seller/at-a-glance.html/ref=dp_merchant_link?ie=UTF8&seller=A3ANTTIDFSTRNP&isAmazonFulfilled=1')
  search_box = driver.find_element_by_css_selector('.a-unordered-list.a-nostyle.a-vertical') # name属性で検索ボックスを特定
  # search_box.send_keys('スクレイピング') # 検索ボックスにテキストを入力
  # search_box.submit() # 検索文言の送信（検索ボタンを押すのと同じ）
  # time.sleep(2) # 2秒待機

  print(search_box)

# 業者ページを開く
def moveGyousya() :
  # url = "https://www.amazon.co.jp/%E5%8B%9D%E9%80%A3%E6%BC%81%E6%A5%AD%E5%8D%94%E5%90%8C%E7%B5%84%E5%90%88-%E9%95%B7%E6%9C%9F%E4%BF%9D%E5%AD%98%E5%8F%AF%E8%83%BD%E3%81%AA%E5%A1%A9%E8%94%B5%E3%82%BF%E3%82%A4%E3%83%97-%E6%B2%96%E7%B8%84%E3%81%AE%E7%BE%8E%E3%82%89%E6%B5%B7%E3%81%A7%E3%81%A8%E3%82%8C%E3%81%9F%E6%AD%AF%E3%81%94%E3%81%9F%E3%81%88%E6%8A%9C%E7%BE%A4%E3%81%AE%E6%96%B0%E9%AE%AE%E3%83%A2%E3%82%BA%E3%82%AF-%E6%B3%A8%E7%9B%AE%E6%88%90%E5%88%86%E3%83%95%E3%82%B3%E3%82%A4%E3%83%80%E3%83%B3%E5%90%AB%E6%9C%89-%E3%83%9F%E3%83%8D%E3%83%A9%E3%83%AB%E3%83%BB%E3%83%93%E3%82%BF%E3%83%9F%E3%83%B3%E3%82%82%E8%B1%8A%E5%AF%8C/dp/B00XDLYTSC/ref=sr_1_14?__mk_ja_JP=%E3%82%AB%E3%82%BF%E3%82%AB%E3%83%8A&keywords=%E3%82%82%E3%81%9A%E3%81%8F&qid=1639555924&sr=8-14&th=1"

  # get(url)

  # 販売元のリンクを特定
  str = driver.find_element_by_id('sellerProfileTriggerId')
  link = driver.find_element_by_partial_link_text(str.text)

  # 販売元をクリック
  time.sleep(2) # 2秒待機
  link.click()
  # print(link.text)

# アマゾンを開いて商品を検索する
def findItem(s):
  # 開く
  get('https://www.amazon.co.jp/')

  # 検索欄にキーワードを入力
  driver.find_element_by_id("twotabsearchtextbox").send_keys(s)

  time.sleep(2) # 2秒待機
  # 検索ボタンクリック
  driver.find_element_by_id("nav-search-submit-button").click()
  

# 検索一覧を開く
def listLoop():
  # リンク一覧を取得
  linkstrs = driver.find_elements_by_css_selector('.a-size-base-plus.a-color-base.a-text-normal')

  # リンク一覧を参照
  for str in linkstrs :
    # リンクの文字列でリンクを特定
    link = driver.find_element_by_partial_link_text(str.text)

    time.sleep(2) # 2秒待機
    # リンクを開く
    link.click()

    # 新しいタブに切り替える
    driver.switch_to.window(driver.window_handles[1])

    # 業者ページを開く
    moveGyousya()

    # 新しいタブを閉じる
    driver.close()

    # 閉じたタブから前のタブを切り替える
    driver.switch_to.window(driver.window_handles[0])

# findItem('もずく')


# 商品名称を指定する
findItem('壽屋')

listLoop()

# driver.quit() # ブラウザを閉じる