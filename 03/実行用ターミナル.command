#! /bin/sh

# OpenCVライブラリのインストール先
TARGET_DIR="${HOME}/OpenCV"

# ターミナル起動場所
TERMINAL_DIR=`dirname ${0}`

# まず，ターミナルウィンドウをクリア
clear

# インストール先ディレクトリの存在を確認
if [ ! -d ${TARGET_DIR} ]; then
    echo "\nOpenCVライブラリが見つかりません!\n"
    echo "まず，ライブラリを先にインストールしてください．\n"
    exit 1
fi

# ソースプログラム格納ディレクトリ位置の変数
ans=${TERMINAL_DIR}

# ソースプログラム格納ディレクトリのパス名を取得
echo "\nソースプログラムが置いてあるフォルダを，"
/bin/echo -n "ここへドラッグ＆ドロップしてリターンキーをタイプする -> "

read ans
if [ "${ans}" = "" ]; then
    ans=${TERMINAL_DIR}
fi

# ソースプログラム格納ディレクトリをカレントディレクトリにセット
echo "\ncd ${ans}"
cd "${ans}"

# 画面メッセージの出力
cat <<EOF

OpenCVを用いたプログラムは，このターミナルから起動するようにします．
また，ウィンドウを閉じる前に exit コマンドを実行して下さい．

                                                              Enjoy!

EOF

# インストール済みOpenCVライブラリを使用して，OpenCVプログラムが起動できる
# ための環境変数を設定
export PATH="/usr/bin:/bin:/usr/sbin:/sbin:/usr/local/bin:/opt/X11/bin:${PATH}"
export DYLD_LIBRARY_PATH=".:${HOME}/OpenCV/lib:${HOME}/OpenCV/share/OpenCV/java:${DYLD_LIBRARY_PATH}"
export LD_LIBRARY_PATH=".:${HOME}/OpenCV/lib:${HOME}/OpenCV/share/OpenCV/java:${LD_LIBRARY_PATH}"
export CLASSPATH=".:${HOME}/OpenCV/share/OpenCV/java/*:${CLASSPATH}"

# ライブラリ読み込み指定を反映させたbashrcの生成
OPENCV_RCFILE="${TERMINAL_DIR}/.opencv_bashrc"
if [ -e ${HOME}/.bashrc ]; then
    /bin/cp -f ${HOME}/.bashrc ${OPENCV_RCFILE}
else 
    /bin/cp /dev/null ${OPENCV_RCFILE}
fi
echo "" >> ${OPENCV_RCFILE}
echo 'alias java="java -Djava.library.path=${HOME}/OpenCV/share/OpenCV/java"' >> ${OPENCV_RCFILE}

# コマンドプロンプトを赤文字でセット
export PS1="\e[31mForOpenCV$ \e[m"

# bashを一時ディレクトリにコピー
/bin/cp -f /bin/bash /tmp

# 環境を設定済みシェルに移行
/tmp/bash --rcfile ${OPENCV_RCFILE}

# コピーしたbashおよびrcを削除
if [ -x /tmp/bash ]; then
    /bin/rm -f /tmp/bash
    /bin/rm -f ${OPENCV_RCFILE}
fi

# End of the script
