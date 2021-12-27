import cv2
import time
import datetime
import numpy as np
import tkinter as Tk
import PySimpleGUI as sg
from matplotlib import pyplot as plt
from PIL import ImageFont, ImageDraw, Image

### window の色を設定 ###
sg.theme('Dark Blue 3')

# パラメータ設定
no = 0
choices_1 = ('640 x 480', '1280 x 720', '1920 x 1080')
choices_2 = ('50', '100', '150')

### ボタン関係の設定 ###

# テキスト設定
s_moji = sg.Text('Capture Size', size=(15, 1), font=('メイリオ', 13))
s_sen_1 = sg.Text('_'  * 36)
s_sen_2 = sg.Text('_'  * 36)

# 隠れボタン設定
s_choice_1 = sg.InputCombo(choices_1,
                            default_value='1920 x 1080',
                            key= '-cap size-',
                            size=(20, len(choices_1)),
                            enable_events=True)

s_choice_2 = sg.InputCombo(choices_2,
                            default_value='選択してください',
                            key= '-grid sepa size-',
                            size=(20, len(choices_2)),
                            enable_events=True)

# ラジオボタン設定
s_Radio_1 = sg.Radio('Grid ON', "RADIO1", default=False, size=(8,1), font=('メイリオ', 13), key='-grid on-')
s_Radio_2 = sg.Radio('Grid OFF', "RADIO1", default=True, size=(8, 1), font=('メイリオ', 13),key='-grid off-')
s_Radio_3 = sg.Radio('Grid DIRECT', "RADIO1", default=False, size=(19, 1), font=('メイリオ', 13),key='-grid direct-')

# スライダー設定
s_Slider_1 = sg.Slider((0, 255), 128, 1, orientation='h', size=(32, 15), key='-grid sepa-')

# 実行ボタン設定
s_button_1 = sg.Submit(button_text='Capture', size=(15, 2))
s_button_2 = sg.Submit(button_text='Quit', size=(15, 2))


### 画面レイアウト設定 ###
""" 設定パラメータはフレームでまとめた方が便利 """

layout_0 = sg.Frame('',
                    [
                     [s_moji],
                     [s_choice_1],
                     [s_sen_1],
                     [sg.Frame(layout=[[s_Radio_1, s_Radio_2]],
                                title='Grid spacing 1',
                                title_color='Red',
                                font=('メイリオ', 10),
                                relief=sg.RELIEF_SUNKEN
                               )],
                     [s_Slider_1],
                     [sg.Frame(layout=[[s_Radio_3]],
                                title='Grid spacing 2',
                                title_color='Red',
                                font=('メイリオ', 10),
                                relief=sg.RELIEF_SUNKEN
                               )],
                     [s_choice_2],
                     [s_sen_2],     
                     [s_button_1, s_button_2]
                    ],
                    relief=sg.RELIEF_SUNKEN
                   )

### レイアウト設定 ###
layout_1 = [
            [sg.Image(filename='', key='-IMAGE-'), layout_0]
           ]

### ウィンドウ生成 ###
window = sg.Window('OpenCV Image', layout_1, location=(300, 10))


### capture セクション ###
cap = cv2.VideoCapture(0)

cap.set(3, 1920)
cap.set(4, 1080)
cap.set(5, 30)


### イベントループ ###
while True:
    # 時間測定開始
    t1 = time.perf_counter()

    event, values = window.read(timeout=20)

    if event == 'Quit' or event == sg.WIN_CLOSED:
        break

    _, frame = cap.read()

    frame_1 = frame[90:990, 510:1410]

    if event is None:
        print('exit')
        break
    
    if values['-cap size-']:
        if choices_1[0] == values['-cap size-'][:]:
            frame_1 = frame[0:480, 80:560]
        elif choices_1[1] == values['-cap size-'][:]:
            frame_1 = frame[0:720, 280:1000]
        elif choices_1[2] == values['-cap size-'][:]:
            frame_1 = frame[90:990, 510:1410]

    if values['-grid on-']:
        if values['-grid sepa-']:
            
            # 画像の縦、横サイズを抽出
            img_y, img_x = frame_1.shape[:2]

            # 横線を引く：int(values['-grid sepa-'])から
            # img_yの手前までint(values['-grid sepa-'])おきに白い(BGRすべて255)横線を引く
            frame_1[int(values['-grid sepa-']):img_y:int(values['-grid sepa-']), :, :] = 255

            # 縦線を引く：int(values['-grid sepa-'])から
            # img_xの手前までint(values['-grid sepa-'])おきに白い(BGRすべて255)縦線を引く
            frame_1[:, int(values['-grid sepa-']):img_x:int(values['-grid sepa-']), :] = 255

    elif values['-grid direct-']:
        if choices_2[0] == values['-grid sepa size-'][:]:
            img_y, img_x = frame_1.shape[:2]
            frame_1[int(values['-grid sepa size-']):img_y:int(values['-grid sepa size-']), :, :] = 255
            frame_1[:, int(values['-grid sepa size-']):img_x:int(values['-grid sepa size-']), :] = 255

        elif choices_2[1] == values['-grid sepa size-'][:]:
            img_y, img_x = frame_1.shape[:2]
            frame_1[int(values['-grid sepa size-']):img_y:int(values['-grid sepa size-']), :, :] = 255
            frame_1[:, int(values['-grid sepa size-']):img_x:int(values['-grid sepa size-']), :] = 255

        elif choices_2[2] == values['-grid sepa size-'][:]:
            img_y, img_x = frame_1.shape[:2]
            frame_1[int(values['-grid sepa size-']):img_y:int(values['-grid sepa size-']), :, :] = 255
            frame_1[:, int(values['-grid sepa size-']):img_x:int(values['-grid sepa size-']), :] = 255


    ### 画像の保存 ###
    if event == 'Capture':

        # 日付の取得
        d_today = datetime.date.today()
        dt_now = datetime.datetime.now()
            
        cv2.imwrite('./cnn_act/capture' + str("/") + str(no) + str("_") +
                    str(d_today) + str("_") +
                    str(dt_now.hour) + str("_") +
                    str(dt_now.minute) + str("_") +
                    str(dt_now.second) + '.jpg', frame_1)

        no += 1

    ### FPS 計算 ###
    elapsedTime = time.perf_counter() - t1
    fps = "{:.0f}FPS".format(1/elapsedTime)

    # 画面にfpsを表示
    frame_1 = cv2.putText(frame_1, fps, (15, 35),
                          cv2.FONT_HERSHEY_PLAIN, 2, (0, 255, 255), 2, cv2.LINE_AA)

    # 映像をpng画像に変換してwindow画面を更新
    imgbytes = cv2.imencode('.png', frame_1)[1].tobytes()
    window['-IMAGE-'].update(data=imgbytes)

    if event == 'Quit':
        print('Quit')
        break

# ウィンドウの破棄と終了
window.close()