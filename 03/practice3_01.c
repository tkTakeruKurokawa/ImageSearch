//
// practice3_01: 第1回目の出力ファイルを1つ読み込み、各画像ファイルに含まれる人物顔の数を出力する
//
#include <stdio.h>
#include <core/core.hpp>
#include <highgui/highgui.hpp>
#include <objdetect/objdetect.hpp>

// 物体検出用変数および関数の宣言(今回はこういうものだと思っていてOK)
static CvHaarClassifierCascade* cascade = 0;    // 検出用基準データ用の変数
static CvMemStorage* storage = 0;               // 計算時の一時メモリ用の変数
int detect_and_draw_objects( IplImage* image ); // 物体検出用の関数

int main( int argc, char **argv )
{
	// result_filename: 出力ファイル名用
	// line           : 1行読み込み用
	// img_filename   : 画像ファイル名用
	// text_alt       : alt属性テキスト用
	// text_par       : 親ノードテキスト用
	char *result_filename, line[16384], img_filename[1024], text_alt[16384], text_par[16384];
	// 出力ファイル名生成のための文字列操作用
	char *pos, fc_filename[1024];
	char *pat = "../result", *rep = "../imgfc", *ext=".image_and_face_count";
	FILE *fr, *fw;
	int gap;
	// 画像データ用（IplImage型を指すポインタ）
	IplImage *img=0;
	// 正面人物顔の検出用基準データのファイル名
	const char *cascade_filename = "../haarcascades/haarcascade_frontalface_alt2.xml";
	// 検出した人物顔の数
	int face_count;

	// 引数があればresult_filenameにセット
	result_filename = argc > 1 ? argv[1] : "../result/test_data009.image_and_text";
	// 入力ファイルを読み込み専用モード("r")でオープン。オープン失敗なら終了
	if((fr=fopen(result_filename, "r"))==NULL) {
		printf("input file open error!!\n");
		return -1;
	}
	// 検出用基準データの読み込み
	cascade = (CvHaarClassifierCascade*) cvLoad( cascade_filename, 0, 0, 0 );
	// 読み込み失敗なら終了
	if(!cascade) {
		printf("cascade not successfully loaded!!!\n");
		return -1;
	}
	// 計算時の一時メモリの生成
	storage = cvCreateMemStorage(0);

	// 出力ファイル名の生成
	/* ????? */
	strcpy(fc_filename,result_filename);
	gap = strlen(rep)-strlen(pat);
	pos = strstr(fc_filename,pat);
	if(pos!=NULL){
	  if(gap>0){
	    memmove(pos+gap,pos,strlen(pos)+1);
	  } else if(gap<0){
	    memmove(pos,pos-gap,strlen(pos)+gap+1);
	  }
	}
	memmove(pos,rep,strlen(rep));

	pos = strrchr(fc_filename,'.');
	if(pos!=NULL){*pos='\0';}
	strcat(fc_filename,ext);

	//print("%s\n",fc_filename);

	if((fw=fopen(fc_filename,"w"))==NULL){
	  printf("file open error!!\n");
	  return -1;
	}

	// frからlineに1行読み込み。1行に内容がある限り繰り返す
	while(fgets(line, 16384, fr)) {
		// lineから画像ファイル名、alt属性テキスト、親ノードテキストを読み込み
		sscanf(line, "%s\t%s\t%s\n", img_filename, text_alt, text_par);
		// 画像ファイル名が"../data"を含まなければ、次のループへ(continue)
		if(strstr(img_filename, "../data")==NULL) {
			continue;
		}
		// 画像ファイル名を表示
		printf("%s\n", img_filename);
		// 画像の読み込み
		img = cvLoadImage(img_filename, -1);
		// 読み込み失敗なら、次のループへ(continue)
		if(img==0) {
			printf("%s not found or format unsupported !!!\n", img_filename);
			/* ????? */
			continue;
		}
		// 人物顔検出
		face_count = detect_and_draw_objects(img);
		// 出力ファイルへの、画像ファイル名および人物顔の数の書き出し
		/* ????? */
		fprintf(fw,"%s\t%d\n",img_filename,face_count);
		
		

		// 画像データを解放
		cvReleaseImage(&img);
	}
	// 入力ファイルおよび出力ファイルを閉じる
	fclose(fw);
	fclose(fr);

	return 0;
}

// 顔検出を行う処理の本体
int detect_and_draw_objects( IplImage* image )
{
	CvSeq* faces; // 顔検出結果の保存用
	int i, scale = 1;

	// 物体検出を行うOpenCV関数
	faces = cvHaarDetectObjects( image, cascade, storage, 1.2, 2, CV_HAAR_DO_CANNY_PRUNING, cvSize(40,40), cvSize(0,0) );

	// 人物顔の検出結果数を表示
	printf("%d faces found.\n", faces->total);
	// 顔の各検出位置を、赤の長方形で画像データ上に上書き
	for( i = 0; i < faces->total; i++ ) {
		CvRect *face_rect = (CvRect *) cvGetSeqElem( faces, i );
		cvRectangle( image, cvPoint(face_rect->x*scale,face_rect->y*scale),
		             cvPoint((face_rect->x+face_rect->width)*scale,
		                     (face_rect->y+face_rect->height)*scale),
		             CV_RGB(255,0,0), 3, 8, 0 );
	}
	// 人物顔の検出結果数を返す
	/* ????? */
	return faces->total;
}


