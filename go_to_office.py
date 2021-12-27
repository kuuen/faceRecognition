# coding: utf-8

import keras
import cv2, dlib, pprint, os
import numpy as np
from keras.models import load_model
import time
import datetime
from facenet_pytorch import MTCNN, InceptionResnetV1
from PIL import Image
import requests
import json
import logging
from PIL import ImageFont, ImageDraw, Image

save_dir = r"C:\Users\N030\Desktop\faceRecognition-master\faceRecognition\facedata"

# 類似度の関数
def cos_similarity(p1, p2): 
    return np.dot(p1, p2) / (np.linalg.norm(p1) * np.linalg.norm(p2))

def getSyainData():
  response = requests.get('http://localhost/kinmu/Faces/getFacePaths')
  print(response.status_code)    # HTTPのステータスコード取得
  print(response.text)    # レスポンスのHTMLを文字列で取得

  if response.status_code != 200:
    print('サーバーエラー')
    return

  syaindata = json.loads(response.text)

  for syain in syaindata["result"]:

    if os.path.exists(save_dir):
        # 画像データ取得
      img = Image.open(save_dir + '\\' + syain["facepath"])

      # 顔データを160×160に切り抜き
      img_cropped = mtcnn(img)
      img_embedding = resnet(img_cropped.unsqueeze(0))
      syain["facedata"] = img_embedding.squeeze().to('cpu').detach().numpy().copy()
    else:
      syain["facedata"] = 'NoData'

  return syaindata["result"]

def drowText(x, y, text, frame):
  
  text_x, text_y = (x, y)# テキスト表示する左上の座標
  font_size = 20
  text_color = (0, 255, 0)  # 赤文字, 元がOpenCV画像のためBGR表記
  pil_image = Image.fromarray(frame)
  draw = ImageDraw.Draw(pil_image)
  font_path = "C:/Windows/Fonts/meiryo.ttc"
  
  draw.font = ImageFont.truetype(font_path, font_size)  # font設定
  draw.text((text_x, text_y), text, text_color)  # pil_imageに直接書き込み
  result_image = np.array(pil_image)  # OpenCV/numpy形式に変換
  cv2.imshow("test", result_image)

# 結果ラベル
#res_labels = ['NO MASK!!', 'OK']

# save_dir = "C:/Users/arata/Desktop/1206/face_reco-master/_internal/app_code/live"

#model = load_model('mask_model.h5')

# 顔検出のAI
# image_size: 顔を検出して切り取るサイズ
# margin: 顔まわりの余白
mtcnn = MTCNN(image_size=160, margin=10)

# 切り取った顔を512個の数字にするAI
# 1回目の実行では学習済みのモデルをダウンロードしますので、少し時間かかります。
resnet = InceptionResnetV1(pretrained='vggface2').eval()

# Dlibを始める
detector = dlib.get_frontal_face_detector()

syaindatas = getSyainData()

# Webカメラから入力を開始
red = (0, 0, 255)
green = (0, 255, 0)
fid = 1
cap = cv2.VideoCapture(0)


while True:

    # キーボード入力を処理する関数　引数はミリ秒の待ち時間
  k = cv2.waitKey(100)

  # Escキーが押されたら終了
  if k == 27:
      break

  # カメラの画像を読み込む
  # time.sleep(400/1000) ←　cv2.waitKeyに適切な値を入力したら待たないでよさげ
  ok, frame = cap.read()
  # time.sleep(400/1000)

  if not ok: break
  # 画像を縮小表示する
  frame = cv2.resize(frame, (500, 300))

  # 顔検出
  dets = detector(frame, 1)

  for k, d in enumerate(dets) :

    # 顔の範囲を取得
    #pprint.pprint(d)
    x1 = int(d.left())
    y1 = int(d.top())
    x2 = int(d.right())
    y2 = int(d.bottom())
    # 顔部分を切り取る
    im = frame[y1:y2, x1:x2]

    # 枠を描画
    color = red
    border = 5
    cv2.rectangle(frame, (x1, y1), (x2, y2), color, thickness = border)

    # 顔部分を保存する
    jpgfile = save_dir + '/' + str(fid) + '.jpg'
    cv2.imwrite(jpgfile, frame)
    #cv2.imwrite(jpgfile, im)
    img = Image.open(jpgfile)
    # save_pathを指定すると、切り取った顔画像が確認できます。
    #img_cropped = mtcnn(img, save_path="cropped_img1.jpg")
    img_cropped = mtcnn(img)

    # 正しく顔画像が取得できない場合がある
    if img_cropped == None :
      break

    # 切り抜いた顔データを512個の数字に
    img_embedding = resnet(img_cropped.unsqueeze(0))

    # 512個の数字にしたものはpytorchのtensorという型なので、numpyの方に変換
    p = img_embedding.squeeze().to('cpu').detach().numpy().copy()

    for syaindata in syaindatas:
      # 類似度を計算して顔認証
      res = cos_similarity(p, syaindata['facedata'])

      # 顔データ判定
      if res >= 0.7 :
        dt = datetime.datetime.today()
        t = dt.time()
        if t <= datetime.time(11, 35, 00):
          drowText(x1, y1 - 30, syaindata['user']['name'] + ' さん おはようございます！', frame)
        elif t >= datetime.time(13, 00, 00):
          drowText(x1, y1 - 30, syaindata['user']['name'] + ' さん お疲れ様です！', frame)

cap.release()
cv2.destroyAllWindows()

