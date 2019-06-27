/*
 *	sample3_03: カメラキャプチャ画像からの人物顔検出
 */
#include <stdio.h>
#include <core/core.hpp>
#include <highgui/highgui.hpp>
#include <objdetect/objdetect.hpp>

// 物体検出用変数および関数の宣言(今回はこういうものだと思っていてOK)
static CvHaarClassifierCascade* cascade = 0;     // 検出用基準データ用の変数
static CvMemStorage* storage = 0;                // 計算時の一時メモリ用の変数
void detect_and_draw_objects( IplImage* image ); // 物体検出用の関数

int main( int argc, char** argv )
{
	// frame     : キャプチャ取得画像データ用
	// frame_copy: 画像データ加工用
	IplImage *frame, *frame_copy = 0;
	// 正面人物顔の検出用基準データのファイル名
	const char *cascade_filename = "../haarcascades/haarcascade_frontalface_alt2.xml";
	// カメラキャプチャ用
	CvCapture *capture = 0;

	// 検出用基準データの読み込み
	cascade = (CvHaarClassifierCascade*) cvLoad( cascade_filename, 0, 0, 0 );
	// 読み込み失敗なら終了
	if(!cascade) {
		printf("cascade not successfully loaded!!!\n");
		return -1;
	}
	// 計算時の一時メモリの生成
	storage = cvCreateMemStorage(0);

	// カメラからのキャプチャ用変数をセット
	capture = cvCaptureFromCAM(0);
	// 読み込み失敗なら終了
	if(!capture) {
		printf("capture not successfully loaded!!!\n");
		return -1;
	}

	cvNamedWindow( "Detection result", CV_WINDOW_AUTOSIZE );
	// 全体的な処理を行う無限ループ
	for(;;) {
		// キャプチャから画像フレームを切り出せるかを確認。失敗ならbreak
		if(!cvGrabFrame( capture )) break;
		// キャプチャから画像フレームを画像データとして取得
		frame = cvRetrieveFrame( capture, 0 );
		// 失敗ならbreak
		if(!frame ) break;
		// 画像データ加工用のポインタに、
		// キャプチャした画像データと同じ大きさのメモリ領域を確保（初回のみ実行される）
		if(!frame_copy)
			frame_copy = cvCreateImage( cvSize(frame->width,frame->height), IPL_DEPTH_8U, frame->nChannels );
		// データ加工用変数に、画像データをコピー
		// （キャプチャから直接取得した画像データframeを加工することは推奨されないため）
		cvCopy( frame, frame_copy, 0 );

		// 人物顔検出。結果をデータ加工用変数に上書き
		detect_and_draw_objects( frame_copy );
		cvShowImage("Detection result", frame_copy);

		// 少し時間をおく。キー入力があればbreak
		if( cvWaitKey( 10 ) >= 0 ) break;
	}

	// 使い終わったらメモリを解放
	cvDestroyWindow("Detection result");
	cvReleaseHaarClassifierCascade( &cascade );
	cvReleaseImage( &frame_copy );
	cvReleaseCapture( &capture );
	cvReleaseMemStorage( &storage );

	return 0;
}

// 顔検出を行う処理の本体
void detect_and_draw_objects( IplImage* image )
{
	CvSeq* faces; // 顔検出結果の保存用
	int i, scale = 1;

	// 物体検出を行うOpenCV関数
	faces = cvHaarDetectObjects( image, cascade, storage, 1.2, 2, CV_HAAR_DO_CANNY_PRUNING, cvSize(40,40), cvSize(0,0));

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
}

