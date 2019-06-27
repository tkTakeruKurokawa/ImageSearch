#
# グラフィックスI: OpenCVプログラミング向け Makefile
#
# 2016/09/02版
#
# 使い方
#
# C言語ソースプログラム mysource.c, 
# 或いは，C++言語ソースプログラム mysource.cpp を
# コンパイルする場合
#	make mysource
# とすると実行可能形式プログラム mysource が生成される．
#
# また，
#	make all
# を実行すると，全てのソースプログラムに対して実行可能形式プログラムが
# 生成される．
#
# 尚，
#	make clean
# にて，全ての実行可能形式プログラムが削除される．
#

OPENCV_DIR = $(HOME)/OpenCV

TARGETS = $(basename $(wildcard *.c)) $(basename $(wildcard *.cpp))

CC	= gcc
CFLAGS	= -I$(OPENCV_DIR)/include/{.,opencv2}
LDFLAGS	= -L$(OPENCV_DIR)/lib
LDLIBS  = -lopencv_core -lopencv_highgui -lopencv_imgproc -lopencv_legacy -lopencv_ocl -lopencv_objdetect

CXX	= g++
CXXFLAGS = $(CFLAGS) -stdlib=libstdc++
# Mac OS X Lion環境の場合，上記の CXXFLAGS 行をコメントアウトし，さらに，
# 代わりに以下の CXXFLAGS 行のコメントアウトを外して有効になるように修正
# してください．
#CXXFLAGS = $(CFLAGS)

.PHONY: all
all: $(TARGETS)
	@echo "実行可能形式プログラムをコンパイルしました．"

.PHONY: clean
clean:
	rm -f $(TARGETS) a.out
	rm -f *.o
	@echo "実行可能形式プログラムを削除しました．"
