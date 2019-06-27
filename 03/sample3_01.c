/*
 *	sample3_01: 画像ファイルの読み込みと表示
 */
#include <core/core.hpp>
#include <highgui/highgui.hpp>

int main( int argc, char **argv )
{
	char *src_name;      // 画像ファイル名用（文字列を指すポインタ）
	IplImage *src_img=0; // 画像データ用（IplImage型を指すポインタ）

	// 引数があればsrc_nameにセット
	src_name = argc > 1 ? argv[1] : "lena.jpg";
	// 画像の読み込み
	src_img = cvLoadImage(src_name, -1);
	// 読み込み失敗なら終了
	if(src_img==0) return -1;

	// 表示用ウィンドウの準備
	cvNamedWindow("Source", CV_WINDOW_AUTOSIZE);
	// 表示用ウィンドウに画像データを渡す
	cvShowImage("Source", src_img);
	// 何かキー入力があるまで待つ
	cvWaitKey(0);

	// 表示用ウィンドウを解放
	cvDestroyWindow("Source");
	// 画像データを解放
	cvReleaseImage(&src_img);

	return 0;
}

