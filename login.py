# -*- coding: utf-8 -*-

import PySimpleGUI as sg
import print

sg.theme('Dark Blue 3')

layout = [
    [sg.Text('ログインID', size=(45, 1))],
    [sg.Input('', key = 'loginId')],
    [sg.Text('パスワード', size=(45, 1))],
    [sg.Input('', key = 'password', password_char='●')],
    [sg.Button('ログイン', key='-login-')],
    [[sg.Checkbox('スワードを記憶する', key = 'passKey')]]
]

# ウィンドウ生成
window1 = sg.Window('ログイン', layout)
index = 0

while True:
    event, values = window1.read()

    if event is None:
        break

    if event == '-login-':
        disp_text = ''
        print.display()

# ウィンドウ破棄
window1.close()

