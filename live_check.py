import keras
import cv2, dlib, pprint, os
import numpy as np
from keras.models import load_model
import time
from facenet_pytorch import MTCNN, InceptionResnetV1
from PIL import Image

# 結果ラベル
#res_labels = ['NO MASK!!', 'OK']
save_dir = "./live"
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

# Webカメラから入力を開始
red = (0, 0, 255)
green = (0, 255, 0)
fid = 1
cap = cv2.VideoCapture(0)

# 認識データ
image_path1 = "images1.jpg"
image_path2 = "images2.jpg"

# 画像データ取得
img1 = Image.open(image_path1)

# 顔データを160×160に切り抜き
img_cropped1 = mtcnn(img1)

# with open(image_path1, 'rb') as rf:# 追加
#   img_buf= np.frombuffer(rf.read(), dtype=np.uint8)# 追加
#   img = cv2.imdecode(img_buf, cv2.IMREAD_UNCHANGED)# 追加
# ret, encoded  = cv2.imencode(".jpg", img)# 追加
# frame = cv2.resize(encoded, (500, 300))# 追加
# img_cropped1 = detector(frame, 1) # 追加

img_embedding1 = resnet(img_cropped1.unsqueeze(0))

# 登録二人目
img_embedding2 = None
if os.path.exists(image_path2):
  img2 = Image.open(image_path2)
  img_cropped2 = mtcnn(img2)
  img_embedding2 = resnet(img_cropped2.unsqueeze(0))

# 類似度の関数
def cos_similarity(p1, p2): 
    return np.dot(p1, p2) / (np.linalg.norm(p1) * np.linalg.norm(p2))

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
    pprint.pprint(d)
    x1 = int(d.left())
    y1 = int(d.top())
    x2 = int(d.right())
    y2 = int(d.bottom())
    # 顔部分を切り取る
    im = frame[y1:y2, x1:x2]

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
    p1 = img_embedding1.squeeze().to('cpu').detach().numpy().copy()

    # 類似度を計算して顔認証
    imgvs1 = cos_similarity(p, p1)

    print("images1さんの判定 : ", imgvs1, "%")

    if img_embedding2 == None:
      if os.path.exists(image_path2):
        img2 = Image.open(image_path2)
        img_cropped2 = mtcnn(img2)
        img_embedding2 = resnet(img_cropped2.unsqueeze(0))

        p2 = img_embedding2.squeeze().to('cpu').detach().numpy().copy()
        imgvs2 = cos_similarity(p, p2)
        print("images2さんの判定 : ", imgvs2, "%")
    else :
      p2 = img_embedding2.squeeze().to('cpu').detach().numpy().copy()
      imgvs2 = cos_similarity(p, p2)
      print("images2さんの判定 : ", imgvs2, "%")

    # 枠を描画
    color = red
    border = 5
    cv2.rectangle(frame, (x1, y1), (x2, y2), color, thickness = border)

  # カメラの内容を画面に表示する
  cv2.imshow('test', frame)

cap.release()
cv2.destroyAllWindows()
print("test")
