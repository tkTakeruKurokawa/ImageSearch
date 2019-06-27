//
// Practice4_01: キーワードと画像ファイル名の並びへの変換
//               （キーワードの昇順、キーワードの出現頻度の昇順にソート）
//
import java.io.*; // ファイル入出力に必要なパッケージのimport
import java.util.*; // HashSet, TreeMap, ArrayListに必要なパッケージのimport

public class Practice4_01 {
	public static void main(String args[]) throws Exception{
		String inFileName = "imgtf.all";	 // 入力ファイル名
		String outFileName = "tfimg.all"; // 出力ファイル名

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

			// キーワード(String)をキー、###を値とするTreeMapの生成。###は、頻度(Integer)をキー、画像ファイル名(String)の配列を値とするTreeMap
			//ArrayList<String> data = new ArrayList<string>();
			//Map<Integer,ArrayList<String>> temp = new TreeMap<Integer,ArrayList<String>>();
			Map<String,TreeMap<Integer,ArrayList<String>>> tf2imgs = new TreeMap<String,TreeMap<Integer,ArrayList<String>>>();
			
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

				// キーワード(word)が初めて出てきたキーならば、そのキーに対応する値を入れるためのTreeMapを生成する
				if(!tf2imgs.containsKey(word)){
				    // キーワード(word)に対応する値(###としていた部分)について、そのキーワード頻度(count)が初めて出てきたキーならば、そのキーに対応する値を入れるためのArrayListを生成する
				    tf2imgs.put(word,new TreeMap<Integer,ArrayList<String>>());
				}
				// キーワード(word)に対応する値(###としていた部分)について、そのキーワード頻度(count)に対応する値に、画像ファイル名を追加する
				if(!tf2imgs.get(word).containsKey(count)){
				    tf2imgs.get(word).put(count, new ArrayList<String>());
				}
				tf2imgs.get(word).get(count).add(img);
			}
			// イテレータを使ってTreeMapの中身を出力ファイルに出力
			Iterator<String> itw = tf2imgs.keySet().iterator();
			while(itw.hasNext()){
			    String word = itw.next();
			    Iterator<Integer> itc = tf2imgs.get(word).keySet().iterator();
			    while(itc.hasNext()){
				Integer count = itc.next();
				Iterator<String> its = tf2imgs.get(word).get(count).iterator();
				while(its.hasNext()){
				    String imgstr = its.next();
				    String outText = word + "\t" + count + "\t" + imgstr + "\n";
				    bw.write(outText);
				}
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
		} catch(Exception e) { e.printStackTrace(); } // 例外発生時の状況を表示する
	}
}
