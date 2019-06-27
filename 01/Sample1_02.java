//
// Sample1_02: 与えられたWebページを読み込み、ページに含まれるアンカーテキストとURIを全て表示する
//
import org.cyberneko.html.parsers.DOMParser;
import org.w3c.dom.*;

public class Sample1_02 {
	public static void main(String args[]) throws Exception{
		// DOMを解釈するパーサを準備
		DOMParser parser = new DOMParser();
		parser.setFeature("http://xml.org/sax/features/namespaces", false);

		// 引数があれば第１引数をurlStrにセット、なければ"test.html"をセット
		String urlStr = args.length>0 ? args[0] : "test.html";
                System.out.println("SOURCE URL: " + urlStr);

		try {
			// urlStrのファイルをパーサで読み込み、parserにDOMツリーを生成
			parser.parse(urlStr);
			// parser内のDOMツリーのドキュメントノードをdocumentにセットする
			Document document = parser.getDocument();
			// "a"をタグ名にもつ要素を全て取得する
			NodeList nodeList = document.getElementsByTagName("a");

			// 各要素のテキスト内容とリンク先(=href属性の値)を表示する
			for(int i=0; i < nodeList.getLength(); i++){
				Element element = (Element)nodeList.item(i);
				System.out.println("|" + element.getTextContent() + "|" + element.getAttribute("href") + "|");
			}
		} catch(Exception e) { e.printStackTrace(); } // 例外発生時の状況を表示する
	}
}
