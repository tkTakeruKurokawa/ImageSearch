//
// Sample4_01: 人数と画像ファイル名の並びへの変換
//
import java.io.*; // ファイル入出力に必要なパッケージのimport

public class Sample4_01 {
	public static void main(String args[]) throws Exception{
		String inFileName = "imgfc.all";	 // 入力ファイル名
		String outFileName = "fcimg.all.sample"; // 出力ファイル名

		try {
			// 入力ファイルのオープン
			FileInputStream fis = new FileInputStream(inFileName);
			InputStreamReader isr = new InputStreamReader(fis , "UTF-8");
			BufferedReader br = new BufferedReader(isr);
			// 出力ファイルのオープン
			FileOutputStream fos = new FileOutputStream(outFileName);
			OutputStreamWriter osw = new OutputStreamWriter(fos , "UTF-8");
			BufferedWriter bw = new BufferedWriter(osw);
			// 1行読み込み用
			String line;

			// 入力ファイルから1行読み込み、中身がnullでない限り繰り返し
			while((line=br.readLine()) != null) {
				// 1行の中の改行文字を全て削除し、タブで分割
				String[] item = line.replaceAll("\n", "").split("\t");
				// 分割結果の0番目は画像ファイル名
				String img = item[0];
				// 分割結果の1番目は人数(Integerに変換)
				Integer fc = Integer.valueOf(item[1]);
				// 人数が0の場合は次のループへ
				if(fc==0) continue;
				// 人数＋タブ＋画像ファイル名の順で、出力ファイルに書き込み
				String outText = fc + "\t" + img + "\n";
				bw.write(outText);
			}

			// 使い終わったら閉じる（出力ファイル）
			bw.close();
			osw.close();
			fos.close();
			// 使い終わったら閉じる（入力ファイル）
			br.close();
			isr.close();
			fis.close();
		} catch(Exception e) { e.printStackTrace(); } // 例外発生時の状況を表示する
	}
}
