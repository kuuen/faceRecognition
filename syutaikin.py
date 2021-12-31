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
import PySimpleGUI as sg

save_dir = "./facedata"

# なぜかkey='name'が指定されていないとデバック中エラー表示が出てしまうので隠す
layout = [
  					[sg.Text('顔認証', size=(40, 1), justification='center', font='Helvetica 20',key='-status-')],
						[sg.Text(key='name', visible= False)],
						[sg.Image(filename='', key='image')],
            [sg.Button('顔登録開始', key='start'), sg.Button('停止', key = 'stop'), sg.Button('キャンセル', key = 'cancel')] 
        ]

window = sg.Window('顔登録', layout, location=(100, 100))	

recording = False

save_dir = "./facedata"

mtcnn = MTCNN(image_size=160, margin=10)
# Dlibを始める
detector = dlib.get_frontal_face_detector()

# 切り取った顔を512個の数字にするAI
# 1回目の実行では学習済みのモデルをダウンロードしますので、少し時間かかります。
resnet = InceptionResnetV1(pretrained='vggface2').eval()

cap = None
# Webカメラから入力を開始
red = (0, 0, 255)
green = (0, 255, 0)
# fid = 1

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

def sendSyukinData():
  response = requests.get('http://localhost/kinmu/Facesattendances/syukkin/%s/%s')
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


# 類似度の関数
def cos_similarity(p1, p2): 
    return np.dot(p1, p2) / (np.linalg.norm(p1) * np.linalg.norm(p2))

def drowText(x, y, text, frame):
  
	text_x, text_y = (x, y)# テキスト表示する左上の座標
	font_size = 20
	text_color = (0, 255, 0)  # 赤文字, 元がOpenCV画像のためBGR表記
	pil_image = Image.fromarray(frame)
	draw = ImageDraw.Draw(pil_image)
	font_path = "C:/Windows/Fonts/meiryo.ttc"

	draw.font = ImageFont.truetype(font_path, font_size)  # font設定
	draw.text((text_x, text_y), text, text_color)  # pil_imageに直接書き込み

	#　ウインドウに描画
	imgbytes = cv2.imencode('.png', frame)[1].tobytes() 
	window['image'].update(data = imgbytes)	
  # result_image = np.array(pil_image)  # OpenCV/numpy形式に変換
  # cv2.imshow("test", result_image)


def kensyutu():
	'''顔検出
	'''

	window['-status-'].update('顔検出中')
	result = ''
	ret, frame = cap.read()
	if ret is True:
		imgbytes = cv2.imencode('.png', frame)[1].tobytes() 
		window['image'].update(data=imgbytes)
	else:
		return

	# 画像を縮小表示する
	# frame = cv2.resize(frame, (500, 300))

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
		jpgfile = save_dir + '/1.jpg'
		cv2.imwrite(jpgfile, frame)
		#cv2.imwrite(jpgfile, im)
		img = Image.open(jpgfile)
		# save_pathを指定すると、切り取った顔画像が確認できます。
		#img_cropped = mtcnn(img, save_path="cropped_img1.jpg")
		img_cropped = mtcnn(img)

		# 正しく顔画像が取得できない場合がある
		if img_cropped == None :
			return

		# 切り抜いた顔データを512個の数字に
		img_embedding = resnet(img_cropped.unsqueeze(0))

		# 512個の数字にしたものはpytorchのtensorという型なので、numpyの方に変換
		p = img_embedding.squeeze().to('cpu').detach().numpy().copy()

		flg = False
		for syaindata in getSyainData():
			# 類似度を計算して顔認証
			res = cos_similarity(p, syaindata['facedata'])

			# 顔データ判定
			if res >= 0.7 :
				flg = True
				dt = datetime.datetime.today()
				t = dt.time()
				if t <= datetime.time(11, 35, 00):
					# drowText(x1, y1 - 30, syaindata['user']['name'] + ' さん おはようございます！', frame)
					window['-status-'].update(syaindata['user']['name'] + ' さん おはようございます！')



				elif t >= datetime.time(13, 00, 00):
					# drowText(x1, y1 - 30, syaindata['user']['name'] + ' さん お疲れ様です！', frame)
					window['-status-'].update(syaindata['user']['name'] + ' さん お疲れ様です！')

		if flg == False:
			window['-status-'].update('顔検出中')

		# 枠を描画
		color = red
		border = 5
		cv2.rectangle(frame, (x1, y1), (x2, y2), color, thickness = border)

		#　ウインドウに描画
		imgbytes = cv2.imencode('.png', frame)[1].tobytes() 
		window['image'].update(data=imgbytes)

def face_registration(id, path) :

		# url = 'http://localhost/kinmu/faces/insert?user_id=%s&path=%s'% (str(id), path)
	url = 'http://localhost/kinmu/faces/insert/%s/%s'% (str(id), path)
	# url = 'http://localhost/kinmu/faces/insert'
	# data = {'user_id': str(id), 'path' : path}

	response = requests.get(url)
	# response = requests.post(url, data)
	scode = response.status_code
	print(scode)    			# HTTPのステータスコード取得
	print(response.text)  # レスポンスのHTMLを文字列で取得

	if scode == '200' or response.text == 'OK':
		return True
	else:
		return False

while True:
	event, values = window.read(timeout=20)
	if event in (None, 'cancel'):
			break
	elif event == 'start':

		window['-status-'].update('Live')
		camera_number = 0
		if cap == None:
			cap = cv2.VideoCapture(camera_number, cv2.CAP_DSHOW)
			# cap = cv2.VideoCapture(camera_number)

			# Dlibを始める
			detector = dlib.get_frontal_face_detector()
			recording = True

	elif event == 'stop':
		window['-status-'].update("Stop")
		recording = False
		# 幅、高さ　戻り値Float
		W = int(cap.get(cv2.CAP_PROP_FRAME_WIDTH))
		H = int(cap.get(cv2.CAP_PROP_FRAME_HEIGHT))
		# print(H,W)
		img = np.full((H, W), 0)
		# ndarry to bytes
		imgbytes = cv2.imencode('.png', img)[1].tobytes()
		window['image'].update(data=imgbytes)
		cap.release()
		cv2.destroyAllWindows()

	if recording:
		kensyutu()
		# path = kensyutu()
		# if path != '':

		# 	if face_registration(values['id'], path) :
		# 		window["id"].update('')
		
		# 		sg.popup_ok('顔登録しました')
		# 	else:
		# 		sg.popup_error('顔登録サーバーエラー')
		# 	window['-status-'].update('待機中')

window.close()
