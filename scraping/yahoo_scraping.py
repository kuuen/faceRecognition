import time
from selenium import webdriver
import os
import shutil
import openpyxl
from selenium.webdriver.common.keys import Keys
from selenium.common.exceptions import NoSuchElementException

# ★導入方法★
# pip install selenium
# https://sites.google.com/chromium.org/driver/downloads?authuser=0 にて使用しているchromeのバージョン
# のwebdriverをダウンロードする
# ダウンロードしたexeファイルをpython.exeと同じディレクトリに保存する（場所は環境変数で確認できる）

driver = webdriver.Chrome() # WebDriverのインスタンスを作成く

def get(url) :
  driver.get(url)
  time.sleep(2) # 2秒待機

# Yahooショッピングを開いて商品を検索する
def findItem(s):
  # 開く
  get('https://shopping.yahoo.co.jp/')

  # 検索欄にキーワードを入力
  driver.find_element_by_id("ss_yschsp").send_keys(s)

  time.sleep(2) # 2秒待機
  # 検索ボタンクリック
  driver.find_element_by_id("ss_srch_btn").click()

def scrollByElemAndOffset(element, offset = 0):

    driver.execute_script("arguments[0].scrollIntoView();", element)

    if (offset != 0):
        script = "window.scrollTo(0, window.pageYOffset + " + str(offset) + ");"
        driver.execute_script(script)

# 検索一覧を開く
# ★30件ごとに改ページ（商品が下に追加）
def listLoop(page, linkstrs, linkRireki):
  # リンク一覧を取得
  # linkstrs = driver.find_elements_by_class_name('_2EW-04-9Eayr')

  # 下スクロールする
  # driver.find_element_by_tag_name('body').click()
  # driver.find_element_by_tag_name('body').send_keys(Keys.PAGE_DOWN)

  # リンクの文字列がまったく同じものがある
  # linkRireki = {}

  # ページ
  # page = 0

  # ページindex
  index = 0

  # 改ページ時のスキップ数
  skipcount = 0

  # i = 1
  # リンク一覧を参照
  for str in linkstrs :
    # ページのスキップ
    if page > 1 and skipcount != -1:
      skipcount += 1

      if ((page - 1) * 40) >= skipcount:
        continue
      else:
        skipcount = -1

    # リンクの文字列でリンクを特定
    links = driver.find_elements_by_partial_link_text(str.text)

    # リンクが複数ある場合は工夫が必要
    if len(links) == 1 :
      link = links[0]
      linkRireki[str.text] = 0
    else:
      # 特定のリンクを指定する
      if  linkRireki.get(str.text) == None:
        linkRireki[str.text] = 0

      link = links[linkRireki[str.text]]
      linkRireki[str.text] += 1

    # 1行舐めたらスクロールする
    # if i % 5 == 0:
      # 下スクロールする
      # driver.find_element_by_tag_name('body').click()
      # driver.find_element_by_tag_name('body').send_keys(Keys.PAGE_DOWN)
      # scrollByElemAndOffset(link, -10)

    # i += 1

    # リンクを開く
    link.click()
    time.sleep(2) # 2秒待機

    # 新しいタブに切り替える
    driver.switch_to.window(driver.window_handles[1])

    # 業者ページを参照
    referGyousya()

    # 新しいタブを閉じる
    driver.close()

    # 閉じたタブから前のタブを切り替える
    driver.switch_to.window(driver.window_handles[0])
    index += 1

    # if index == 30:
    #   page += 1
    #   index = 0
    #   skipcount = 0
  return index

# findItem('もずく')

def referGyousya() :

  link = None
  list = None
  try :
    link = driver.find_element_by_partial_link_text('会社概要')
    list = referGyousyaYahoo()
  except NoSuchElementException :
    list = referGyousyaPayPay()

  if list != None:
    # エクセル書き込み
    witeExcel(list)

# 業者ページを開くPayPayモールの場合
def referGyousyaPayPay() :

  try :
    linkstr = driver.find_element_by_class_name('ItemSeller_name')
  except NoSuchElementException:
    # ページが見つからない場合があった
    return 

  link = driver.find_element_by_partial_link_text(linkstr.text)
  # 販売元をクリック
  link.click()
  time.sleep(2) # 2秒待機

  try :
    # 会社概要・お買い物ガイドクリック
    linkstr = driver.find_element_by_class_name('StoreService_item')
  except NoSuchElementException :
    # ページが見つからない場合があった
    return

  link = driver.find_element_by_partial_link_text(linkstr.text)

# 業者ページを開く
def referGyousyaYahoo() :
  # 会社概要を開く
  link = driver.find_element_by_partial_link_text('会社概要')

  # 販売元をクリック
  link.click()
  time.sleep(2) # 2秒待機
  
  # 情報領域の内容を取得
  zyouhous = driver.find_elements_by_class_name('elRow')

  list = {}
  taisyou = True
  # 情報領域全体を参照する
  for zyouhou in zyouhous:
    # 列名、値を分割する
    ss = zyouhou.text.split('\n')

    # 各値を整理する

    # 住所列の場合、
    if ss[0].find('住所') > -1:
      # # 沖縄以外の場合は対象外　★テストで今だけコメントアウト
      # if ss[1].find('沖縄') == -1 :
      #   taisyou = False
      #   break
      # else :
        # if 'adress' in list:
        #   list['adress1'] = ss[1]
        # else:
        #   list['adress2'] = ss[1]

      if 'adress1' not in list:
        list['adress1'] = ss[1]
      else:
        list['adress2'] = ss[1]
        
    elif ss[0].find('会社名（商号）') > -1:
      list['companyName'] = ss[1]
    elif ss[0].find('郵便番号') > -1:
      list['postCode'] = ss[1]
    elif ss[0].find('代表者') > -1:
      list['representative'] = ss[1]
    elif ss[0].find('ストア名（フリガナ）') > -1:
      list['shopNameKana'] = ss[1]
    elif ss[0].find('ストア名') > -1:
      list['shopName'] = ss[1]
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
    return None
  else :
    return list

def kaisyaExist(companyName, sheet) :
  gyouNo = 2

  result = False
  # 書き込む行番号の決定。空白行まで移動する
  while True :
    if sheet.cell(column = 1, row = gyouNo).value == None:
      result = False
      break
    if sheet.cell(column = 1, row = gyouNo).value == companyName:
      result = True
      break

    gyouNo += 1
  return result

def witeExcel(list):
  # ファイルパスを指定
  # path = r'C:\Users\N030\Desktop\faceRecognition-master\faceRecognition\scraping'
  filename = '店情報.xlsx'
  
  # Bookの読み込み
  wb = openpyxl.load_workbook(filename)

  # シートの読み込み
  sheet = wb['yahoo']
  
  # 既に会社情報が載っている場合は何もしない
  if kaisyaExist(list['companyName'], sheet) :
    return

  gyouNo = 1

  # 書き込む行番号の決定。空白行まで移動する
  while True :
    gyouNo += 1

    if sheet.cell(column = 1,row = gyouNo).value == None:
      sheet.cell(column = 1,row = gyouNo).value = gyouNo - 1
      break

  # 会社名
  sheet.cell(column = 2, row = gyouNo).value = list['companyName']

  # メールアドレス
  sheet.cell(column = 3, row = gyouNo).value = list['mail']

  # 郵便番号
  sheet.cell(column = 4, row = gyouNo).value = list['postCode']

  # 住所1
  sheet.cell(column = 5, row = gyouNo).value = list['adress1']

  # 住所2
  sheet.cell(column = 6, row = gyouNo).value = list['adress2']

  # 代表者
  sheet.cell(column = 7, row = gyouNo).value = list['representative']

  # ストア名
  sheet.cell(column = 8, row = gyouNo).value = list['shopName']

  # ストア名（フリガナ）
  sheet.cell(column = 9, row = gyouNo).value = list['shopNameKana']

  # ストア紹介
  sheet.cell(column = 9, row = gyouNo).value = list['setumei']

  # 運営責任者
  sheet.cell(column = 9, row = gyouNo).value = list['operationManager']

  # 電話番号
  sheet.cell(column = 10, row = gyouNo).value = list['tel']

  # お問い合わせファックス番号 無い店舗もある
  if 'fax' in list :
    sheet.cell(column = 11, row = gyouNo).value = list['fax']

  # ストア営業日/時間
  sheet.cell(column = 12, row = gyouNo).value = list['Time']

  # ここで保存
  wb.save(filename)

    # list[]
    # print(zyouhou.text)

  # if adress.text

# 商品名称を指定する
# findItem('pixel')
findItem('少女週末旅行 1 BD')


linkstrs = driver.find_elements_by_class_name('_2EW-04-9Eayr')
page = 0
index = 1
# 商品リンクの文字列がまったく同じものがある
linkRireki = {}

# 注意ループ条件をループ内で変更している
# リンクが30で割り切れない場合
# while len(linkstrs) % 30 == 0 or len(linkstrs) == page * 30
while True :
  page += 1
  # 30件ごとにページが再読み込みがかかる
  index += listLoop(page, linkstrs, linkRireki)

  # リンクを再読み込み
  linkstrs = driver.find_elements_by_class_name('_2EW-04-9Eayr')

  # 再読み込みしても商品数が増えていなかったら最終ページと判断できる
  if index == len(linkstrs) :
    break

driver.quit() # ブラウザを閉じる
