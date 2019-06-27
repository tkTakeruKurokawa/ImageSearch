import org.chasen.mecab.*;
import java.util.*;
import java.io.*;
import java.util.regex.*;

public class mecabtest {
  static {
    try {
       System.loadLibrary("MeCab");
    } catch (UnsatisfiedLinkError e) {
       System.err.println("Cannot load the example native code.\nMake sure your LD_LIBRARY_PATH contains \'.\'\n" + e);
       System.exit(1);
    }
  }
    
    public static void main(String args[]) throws Exception{
	try{
	    String imgstr = args.length>0 ? args[0] : "test.image_and_text";
	    String imgtfFileName = imgstr.replaceAll("../result","../imgtf").replaceAll("image_and_text","image_and_tf");
	    
	    FileInputStream fis = new FileInputStream(imgstr);
	    InputStreamReader ir = new InputStreamReader(fis,"UTF-8");
	    BufferedReader br = new BufferedReader(ir);

	    FileOutputStream fos = new FileOutputStream(imgtfFileName);
	    OutputStreamWriter osw = new OutputStreamWriter(fos,"UTF-8");
	    BufferedWriter bw = new BufferedWriter(osw);

	    Pattern pattern;
	    Matcher matcher;
	    
	    
	    pattern = Pattern.compile(".*\\.jpg.*|.*\\.png.*|.*\\.bmp.*|.*\\.JPG.*|.*\\.PNG.*|.*\\.BMP.*");
	
	    
	    String line;
	    while((line = br.readLine()) != null){
		String[] terms = line.split("[ \t]");
		//System.out.println(line);


		
		
		matcher = pattern.matcher(terms[0]);
		boolean b = matcher.matches();
		
		if(b){
		    
		    for(Integer i = 0; i<terms.length ;i++){
			if( i!=0 && !Objects.equals(terms[i],"NONE")){

			    Tagger tagger = new Tagger();
			    System.out.println(tagger.parse(terms[i]));
			    Node node = tagger.parseToNode(terms[i]);


			    HashMap<String,Integer> termcount = new HashMap<String,Integer>();
				
			    
			    for (;node != null; node = node.getNext()) {
				String[] features = node.getFeature().split(",");
				
				if(features[0].equals("名詞") || features[0].equals("動詞")){
				    System.out.println("表記語：" + node.getSurface());
				    System.out.println(features[0]);
				    System.out.println(features[6]);
				    
				    Integer counter = termcount.get(features[6]);
				    
				    if( termcount.get(features[6])==null ){
					counter = 1;
				    } else {
					counter++;
				    }	
				    
				    termcount.put(features[6],counter);
				    System.out.println(counter);
				    System.out.println();
				}
			    }
			    Set set = termcount.keySet();
			    Iterator iterator = set.iterator();
			    
			    Object object;

			    while(iterator.hasNext()){
				object = iterator.next();
				String outText = terms[0] + "\t" + object + "\t" + termcount.get(object) + "\n";
				bw.write(outText);
			    }
			}
		    }
		}
	
	    }

	    bw.close();
	    osw.close();
	    fos.close();
	    
	    
	    br.close();
	    ir.close();
	    fis.close();
	    
	} catch(Exception e){
	    e.printStackTrace();
	}
	System.out.println ("EOS\n");
    }
}
