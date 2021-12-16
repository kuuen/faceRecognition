import time
from selenium import webdriver

# pip install selenium
# https://sites.google.com/chromium.org/driver/downloads?authuser=0 にて使用しているchromeのバージョン
# のwebdriverをダウンロードする
# ダウンロードしたexeファイルをpython.exeと同じディレクトリに保存する（場所は環境変数で確認できる）

driver = webdriver.Chrome() # WebDriverのインスタンスを作成く

def get(url) :
  driver.get(url)
  time.sleep(2) # 2秒待機

# アマゾンを開いて商品を検索する
def findItem(s):
  # 開く
  get('https://shopping.yahoo.co.jp/')

  # 検索欄にキーワードを入力
  driver.find_element_by_id("ss_yschsp").send_keys(s)

  time.sleep(2) # 2秒待機
  # 検索ボタンクリック
  driver.find_element_by_id("ss_srch_btn").click()

# 検索一覧を開く
def listLoop():
  # リンク一覧を取得
  linkstrs = driver.find_elements_by_class_name('_2EW-04-9Eayr')

  # リンク一覧を参照
  for str in linkstrs :
    # リンクの文字列でリンクを特定
    link = driver.find_element_by_partial_link_text(str.text)

    # リンクを開く
    link.click()
    time.sleep(2) # 2秒待機

    # 新しいタブに切り替える
    driver.switch_to.window(driver.window_handles[1])

    # 業者ページを開く
    moveGyousya()

    # 新しいタブを閉じる
    driver.close()

    # 閉じたタブから前のタブを切り替える
    driver.switch_to.window(driver.window_handles[0])

# findItem('もずく')

# 業者ページを開く
def moveGyousya() :
  # 会社概要を開く
  link = driver.find_element_by_partial_link_text('会社概要')

  # 対象リンクにカーソルを持ってくる
  driver.move_to_element(link)

  # 販売元をクリック
  link.click()
  time.sleep(2) # 2秒待機
  
  # 情報領域の内容を取得
  zyouhous = driver.find_elements_by_class_name('elRow')

  list = {}
  taisyou = False
  # 情報領域全体を参照する
  for zyouhou in zyouhous:
    # 列名、値を分割する
    ss = zyouhou.text.split('\n')

    # 各値を整理する

    # 住所列の場合、
    if ss[0].find('住所') > -1:
      # 沖縄以外の場合は対象外
      if ss[1].find('沖縄') == -1 :
        break
      else :
        try:
          list.index('adress')
        except ValueError :
          list['adress'] = ss[1]
    elif ss[0].find('会社名（商号）') > -1:
      list['companyName'] = ss[1]
    elif ss[0].find('郵便番号') > -1:
      list['postCode'] = ss[1]
    elif ss[0].find('代表者') > -1:
      list['representative'] = ss[1]
    elif ss[0].find('ストア名') > -1:
      list['shopName'] = ss[1]
    elif ss[0].find('ストア名（フリガナ）') > -1:
      list['shopNameKana'] = ss[1]
    elif ss[0].find('ストア紹介') > -1:
      list['setumei'] = ss[1]
    elif ss[0].find('運営責任者') > -1:
      list['operationManager'] = ss[1]
    elif ss[0].find('電話番号') > -1:
      list['tel'] = ss[1]
    elif ss[0].find('お問い合わせファックス番号') > -1:
      list['fax'] = ss[1]
    elif ss[0].find('お問い合わせメールアドレス') > -1:
      list['mail'] = ss[1]
    elif ss[0].find('ストア営業日/時間') > -1:
      list['Time'] = ss[1]

  # 対象外の場合はここで終了
  if taisyou == False:
    return

  
  
    # list[]
    # print(zyouhou.text)

  # if adress.text

# 商品名称を指定する
findItem('壽屋')

listLoop()

# driver.quit() # ブラウザを閉じる