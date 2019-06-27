//
// Sample4_02r: キーワードと画像ファイル名の並びへの変換
//
import java.io.*; // ファイル入出力に必要なパッケージのimport
import java.util.*; // HashSetに必要なパッケージのimport

public class Sample4_02 {
	public static void main(String args[]) throws Exception{
		String inFileName = "imgtf.all";	 // 入力ファイル名
		String outFileName = "tfimg.all.sample"; // 出力ファイル名

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
			// ストップワード一覧の設定
			String stopWords = "*/さん/ちゃん/くん/する/そう/いる/ある/なる/くださる/いただく";
			// ストップワード一覧の各要素をもつ集合を生成
			Set<String> stopWordSet = new HashSet<String>(Arrays.asList(stopWords.split("/")));

			// 入力ファイルから1行読み込み、中身がnullでない限り繰り返し
			while((line=br.readLine()) != null) {
				// 1行の中の改行文字を全て削除し、タブで分割
				String[] item = line.replaceAll("\n", "").split("\t");
				// 分割結果の0番目は画像ファイル名、1番目はキーワード、2番目はキーワード頻度(Integerに変換)
				String img = item[0];
				String word = item[1];
				Integer count = Integer.valueOf(item[2]);

				// wordがストップワードであれば次の繰り返しへ
				if(stopWordSet.contains(word)) continue;

				// キーワード＋タブ＋頻度＋タブ＋画像ファイル名の順に並び替え、出力ファイルに書き込み
				String outText = word + "\t" + count + "\t" + img + "\n";
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
