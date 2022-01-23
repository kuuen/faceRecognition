from PIL import Image
import base64
from io import BytesIO
import os
import cv2
import dlib
import time
import PySimpleGUI as sg
import requests
from bs4 import BeautifulSoup
import threading
from multiprocessing.pool import ThreadPool
import numpy as np
from facenet_pytorch import MTCNN, InceptionResnetV1

layout = [
  					[sg.Text('Realtime movie', size=(40, 1), justification='center', font='Helvetica 20',key='-status-')],
            [sg.Text('社員番号'), sg.Input('', key = 'id'), sg.Text(key = 'name') ,sg.Button('検索', key='find')],
						[sg.Image(filename='', key='image')],
            [sg.Button('顔登録開始', key='start'), sg.Button('停止', key = 'stop'), sg.Button('キャンセル', key = 'cancel')] 
        ]

window = sg.Window('顔登録', layout, location=(100, 100))	

mtcnn = MTCNN(image_size=160, margin=10)

cap = None
# Webカメラから入力を開始
red = (0, 0, 255)
green = (0, 255, 0)
fid = 1


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
	
	# 空白の場合は顔検出しない
	if window["name"].DisplayText == '':
		return ''

	# 顔検出
	dets = detector(frame, 1)

	for k, d in enumerate(dets) :
		x1 = int(d.left())
		y1 = int(d.top())
		x2 = int(d.right())
		y2 = int(d.bottom())
		# 顔部分を切り取る
		im = frame[y1:y2, x1:x2]

		# 顔部分を保存する
		filename = str(time.time()) + '.jpg'
		jpgfile = save_dir + '/' + filename 
		# cv2.imwrite(jpgfile, im)
		cv2.imwrite(jpgfile, frame)

		# 画像データが使用できるか確認
		img = Image.open(jpgfile)
		# 顔データを160×160に切り抜き
		img_cropped = mtcnn(img)

		# 使用できない場合、破棄する
		if img_cropped == None:
			os.remove(jpgfile)
			filename = ''

		# 枠を描画
		color = red
		border = 5
		cv2.rectangle(frame, (x1, y1), (x2, y2), color, thickness = border)
		imgbytes = cv2.imencode('.png', frame)[1].tobytes() 
		window['image'].update(data=imgbytes)

		result = filename

	return result

def face_registration(id, path) :
	''' 顔情報登録
	'''

	with requests.Session() as session :

	# session = requests.Session()

		# サーバーログイン
		with serverLogin(session) as response:

			bs = BeautifulSoup(response.text, "html.parser")
			token = bs.find('input', attrs={ 'name' : '_csrfToken' }).get('value')

			# クッキー取得
			co = response.cookies

		# csrfトークン取得するためにgetUserNameページに接続
		url = 'http://localhost/kinmu/faces/add'
		with session.get(url, headers = {'X-CSRF-Token': token}, cookies = co) as response:

			if response.status_code != 200:
				sg.popup_error('サーバーエラー')
				return ''

			# csrfトークンを取得
			token = bs.find('input', attrs={ 'name' : '_csrfToken' }).get('value')
			he = {'X-CSRF-Token': token}

			# クッキー取得
			co = response.cookies

			f = open("file.html", "w")
			f.write(response.text)

		# 送信情報
		payload = {'user_id': id, 'facepath': path}

		# 名前登録
		with session.post(url, data = payload, headers = he, cookies = co) as response:
			status_code = response.status_code
			rst = response.text

		# # クッキーを取得
		# cookies = response.cookies
		# bs = BeautifulSoup(response.text, "html.parser")

		# # csrfトークンを取得
		# token = bs.find('input', attrs={ 'name' : '_csrfToken' }).get('value')

		# # ヘッダーに追加
		# headers = {'X-CSRF-Token': token}

		# # 送信情報
		# payload = {'user_id': id, 'facepath': path}

		# # 登録処理
		# response = session.post(url, data = payload, headers=headers, cookies=cookies)
		# scode = response.status_code

	# なんかよく失敗する。CSRF対策あたりが原因
	if status_code == 200:
		return True
	else:
		return False

def serverLogin(session):

	url = 'http://localhost/kintai/syains/login'

	# サーバーログイン
	with session.get(url) as response:

		# クッキー取得
		co = response.cookies

		# csrfトークンを取得
		bs = BeautifulSoup(response.text, "html.parser")
		token = bs.find('input', attrs={ 'name' : '_csrfToken' }).get('value')
		payload = {'username': 'sys', 'password' : 123}

	return session.post(url, data = payload, headers = {'X-CSRF-Token': token}, cookies = co)

def getName(id):
	''' 名前取得
	'''

	# セッション作成
	with requests.Session() as session :
		# サーバーにログイン
		with serverLogin(session) as response:

			bs = BeautifulSoup(response.text, "html.parser")
			token = bs.find('input', attrs={ 'name' : '_csrfToken' }).get('value')

			# クッキー取得
			co = response.cookies

		# csrfトークン取得するためにgetUserNameページに接続
		url = 'http://localhost/kinmu/users/getUserName'
		with session.get(url, headers = {'X-CSRF-Token': token}, cookies = co) as response:

			if response.status_code != 200:
				sg.popup_error('サーバーエラー')
				return ''

			# csrfトークンを取得
			token = bs.find('input', attrs={ 'name' : '_csrfToken' }).get('value')
			he = {'X-CSRF-Token': token}

			# クッキー取得
			co = response.cookies

		# 送信情報
		payload = {'id': id}

	# 名前取得
	with session.post(url, data = payload, headers = he, cookies = response.cookies) as response:
		status_code = response.status_code
		rst = response.text

	# f = open("file.html", "w")
	# f.write(response.text)

	# ☆☆判定が不十分
	result = ''
	if status_code == 200:

		if rst == '':
			sg.popup_error('社員が存在しません')
		else:
			result = rst[0:20]
	else :
		sg.popup_error('サーバーエラー')

	return result

recording = False

save_dir = "./facedata"

while True:
	event, values = window.read(timeout=20)
	if event in (None, 'cancel'):
			break
	elif event == 'find':
		window["name"].update(getName(values['id']))
	elif event == 'start':
		if window["name"].DisplayText == '':
				sg.popup_error('社員を決定してください')
				continue

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
		path = kensyutu()
		if path != '':

			if face_registration(values['id'], path) :
				window["id"].update('')
				window["name"].update('')
				sg.popup_ok('顔登録しました')
			else:
				sg.popup_error('顔登録サーバーエラー')
				os.remove(save_dir + '/' + path)

			window['-status-'].update('待機中')

window.close()
