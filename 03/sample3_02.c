/*
 *	sample3_02: 第1回の出力ファイルからの画像ファイル名取得と表示
 */
#include <stdio.h>
#include <core/core.hpp>
#include <highgui/highgui.hpp>

int main( int argc, char **argv )
{
	// result_filename: 出力ファイル名用
	// line           : 1行読み込み用
	// img_filename   : 画像ファイル名用
	// text_alt       : alt属性テキスト用
	// text_par       : 親ノードテキスト用
	char *result_filename, line[16384], img_filename[1024], text_alt[16384], text_par[16384];
	FILE *fp;          // ファイル入力用（ファイル型を指すポインタ）
	IplImage *img=0;

	// 引数があればresult_filenameにセット
	result_filename = argc > 1 ? argv[1] : "../result/test_data009.image_and_text";
	// ファイルを読み込み専用モード("r")でオープン。オープン失敗なら終了
	if((fp=fopen(result_filename, "r"))==NULL) {
		printf("file open error!!\n");
		return -1;
	}
	cvNamedWindow("SOURCE", CV_WINDOW_AUTOSIZE);

	// fpからlineに1行読み込み。1行に内容がある限り繰り返す
	while(fgets(line, 16384, fp)) {
		// lineから画像ファイル名、alt属性テキスト、親ノードテキストを読み込み
		sscanf(line, "%s\t%s\t%s\n", img_filename, text_alt, text_par);
		// 画像ファイル名が"../data"を含まなければ、次のループへ(continue)
		if(strstr(img_filename, "../data")==NULL) {
			continue;
		}
		// 画像ファイル名を表示
		printf("%s\n", img_filename);
		img = cvLoadImage(img_filename, -1);
		// 読み込み失敗ならメッセージを出力して、次のループへ(continue)
		if(img==0) {
			printf("%s not found or format unsupported !!!\n", img_filename);
			continue;
		}
		cvShowImage("SOURCE", img);
		cvWaitKey(0);
		cvReleaseImage(&img);
	}
	cvDestroyWindow("SOURCE");
	// 読み込み用ファイルを閉じる
	fclose(fp);

	return 0;
}

