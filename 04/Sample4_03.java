//
// Sample4_03: 人数と画像ファイル名の並びへの変換
//             （人数の昇順にソート）
//
import java.io.*;   // ファイル入出力に必要なパッケージのimport
import java.util.*; // TreeMap, ArrayListに必要なパッケージのimport

public class Sample4_03 {
	public static void main(String args[]) throws Exception{
		String inFileName = "imgfc.all";  // 入力ファイル名
		String outFileName = "fcimg.all"; // 出力ファイル名

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
			// 人数をキー、文字列のArrayListを値とするTreeMapの生成
			Map<Integer,ArrayList<String>> fc2imgs = new TreeMap<Integer,ArrayList<String>>();

			// 入力ファイルから1行読み込み、中身がnullでない限り繰り返し
			while((line=br.readLine()) != null) {
				// 1行の中の改行文字を全て削除し、タブで分割
				String[] item = line.replaceAll("\n", "").split("\t");
				// 分割結果の0番目は画像ファイル名, 1番目は人数(Integerに変換)
				String img = item[0];
				Integer fc  = Integer.valueOf(item[1]);
				// 人数が0の場合は次のループへ
				if(fc==0) continue;
				// fcが初めて出てきたキーならば、ArrayListを初期化してTreeMapの値として追加
				if(!fc2imgs.containsKey(fc)) fc2imgs.put(fc, new ArrayList<String>());
				// キーfcの値(=文字列のArrayList)に、画像ファイル名imgを追加
				fc2imgs.get(fc).add(img);
			}
            // TreeMapのキーの集合のイテレータ(要素を1つずつ反復する際に使う)をセット
			Iterator<Integer> it = fc2imgs.keySet().iterator();
            // 次のキーがある限り繰り返し
			while(it.hasNext()) {
                // countには、TreeMapのキーが1つずつ入る
				Integer count = it.next();
				// countをキーとするArrayListの要素を1つずつimgstrに代入しながら繰り返し
				for(String imgstr : fc2imgs.get(count)) {
					// 人数＋タブ＋画像ファイル名の順で、画面およびファイルに出力 
					String outText = count + "\t" + imgstr + "\n";
					System.out.print(outText);
					bw.write(outText);
				}
			}

			// 使い終わったら閉じる（出力ファイル）
			bw.close();
			osw.close();
			fos.close();
			// 使い終わったら閉じる（入力ファイル）
			br.close();
			isr.close();
			fis.close();
        } catch (Exception e) { e.printStackTrace(); } // 例外発生時の状況を表示する
	}
}
