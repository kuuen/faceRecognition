import MySQLdb
from PIL import Image
# 修正--------------------------
import base64
from io import BytesIO
import cv2
import dlib
import time
import PySimpleGUI as sg

# DBに保存するするために画像をバイトで変換しようとした
# def imgEncode(path):

#     #image_path1 = "images1.jpg"

#     # img1 = Image.open(path)

#     with open(path, 'rb') as f:
#         img = f.read()

#     #Base64で画像をエンコード
#     encode = base64.b64encode(img)
#     return encode

    # Image.open(BytesIO(base64.b64decode(encode)))

def face_registration(id, path) :
    print("test")

    # 接続する
    conn = MySQLdb.connect(
    user='root',
    passwd='',
    host='localhost',
    db='kinmu')

    cur = conn.cursor()
    sql = ('''INSERT INTO faces (user_id, facepath) VALUES (%s ,%s)''')

    data = [(str(id), path)]

#    sql = "select * from users"

    cur.executemany(sql, data)

    conn.commit()

    # 接続を閉じる
    conn.close()

def kaokensyutu():

    save_dir = "./facedata"

    # Dlibを始める
    detector = dlib.get_frontal_face_detector()

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
        ok, frame = cap.read()

        if not ok: break
        # 画像を縮小表示する
        frame = cv2.resize(frame, (500, 300))

        # カメラの内容を画面に表示する
        cv2.imshow('test', frame)

        # 顔検出
        dets = detector(frame, 1)

        for k, d in enumerate(dets) :
            # 顔の範囲を取得
            x1 = int(d.left())
            y1 = int(d.top())
            x2 = int(d.right())
            y2 = int(d.bottom())
            # 顔部分を切り取る
            im = frame[y1:y2, x1:x2]

            # 顔部分を保存する
            jpgfile = save_dir + '/' + str(time.time()) + '.jpg'
            cv2.imwrite(jpgfile, im)

            cap.release()
            cv2.destroyAllWindows()

            return jpgfile

def getName(id):
    # 接続する
    conn = MySQLdb.connect(
    user='root',
    passwd='',
    host='localhost',
    db='kinmu')

    cur = conn.cursor()
    sql = ('''SELECT name FROM users WHERE id = %s''')

    data = [(str(id))]

#    sql = "select * from users"

    cur.execute(sql, data)

    r = cur.fetchall()

    result = ''
    if len(r) > 0:
        return r[0][0]

    # 接続を閉じる
    conn.close()

    return result

sg.theme('DarkAmber')

#　リストボックスに表示するデータ
choices = ([[1, '赤'], [2, '緑']])

layout = [  [sg.Text('ここは1行目')],
            [sg.Text('社員番号'), sg.Input('', key = 'id'), sg.Text(key='name') ,sg.Button('検索', key='find')],
            [sg.Button('顔登録開始', key='start'), sg.Button('キャンセル')] ]

window = sg.Window('サンプルプログラム', layout)

while True:
    event, values = window.read()
    if event == sg.WIN_CLOSED or event == 'キャンセル':
        break
    elif event == '検索':
        print('あなたが入力した値： ', values[0])
    elif event == 'find':
        window["name"].update(getName(values['id']))
    elif event == 'start':
        path = kaokensyutu()
        face_registration(1, path)
        window["id"].update('')
        window["name"].update('')
        sg.popup_ok('顔登録しました')

window.close()


