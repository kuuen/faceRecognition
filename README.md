# faceRecognition

windows10 64bitでの動作設定  
  
pythonで使用するライブラリをインストール 「pip install ライブラリ名」  
●python-3.9.9-amd64　インストール　  
	https://www.python.org/downloads/  
	「Note that Python 3.9.9 cannot be used on Windows 7 or earlier.」のインストーラー  
	環境変数パス追加オプションを指定  
●cmake-3.22.1-windows-x86_64.msi　インストール   
	https://cmake.org/download/  
●msvcp140_1.dll　インストール  
https://docs.microsoft.com/ja-JP/cpp/windows/latest-supported-vc-redist?view=msvc-170
https://aka.ms/vs/17/release/vc_redist.x64.exe  
●Visual Studio コミュニティのインストール（dlibを使用するために必要）  
Visual Studio Community Editionをインストール後、  
初期表示される開発環境から【C++によるデスクトップ開発】をインストール  
  
  
コマンドプロンプトでライブラリをインストール  
（ライブラリがインストールされているかは「pip list | find "キーワード"」で確認できる）  
python.exe -m pip install --upgrade pip  
pip install keras  
pip install tensorflow  
pip install opencv-python  
pip install dlib  
pip install facenet_pytorch  


images1.jpgを認証する人の顔写真に差し替える  
2人目まで認識できる。ファイル名は images2.jp にする 
  
修正予定  
MTCNN、dlib両方しようしているからどちらか一方に絞りたい  
