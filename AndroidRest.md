# Introduction #

Android Doc
http://developer.android.com/intl/fr/reference/java/util/HashMap.html

Why use JSON ?
http://www.moblitz.com/2009/02/consuming-json-response-in-android.html

Java doc
http://fmora.developpez.com/tutoriel/java/collections/intermediaire/


# Details #


/////// PHP CREATE JSON FROM DB /////////////////////////////////////////////////////////////

```

<?php

require("../bookmark/admin/lib/racine.php");

$C = new Q("SELECT * FROM epg_chaines ORDER BY num_chaine ASC");

$jsonArray = array();

foreach($C->V as $V) {

	$chaine_num = $V['num_chaine'];
	$chaine_titre = ucfirst($V['titre']);
	$chaine_icone = 'images/chanels/'.$V['icone'];

	$jsonArray[$chaine_num] = array(
		'titre' => $chaine_titre,
		'icone' => $chaine_icone
	);
}

header('Content-Type: application/json; charset: UTF-8');
echo json_encode($jsonArray);
die();

?>
```

/////// JSON RESULT /////////////////////////////////////////////////////////////

```
{
	"1": {
		"titre": "Tf1",
		"icone": "images/chanels/tf1.gif"
	},
	"2": {
		"titre": "France 2",
		"icone": "images/chanels/france2.gif"
	},
	...
}
```

/////// ANDROID ACTIVITIES FETCH JSON /////////////////////////////////////////////////////////////

```

@Override
public void onCreate(Bundle savedInstanceState) {
	requestWindowFeature(Window.FEATURE_NO_TITLE);
	
	super.onCreate(savedInstanceState);
	
	try {
		// FECTH HTTP STREAM
		JSONObject jsonChannels = getHttpJson(SITE_URL+"?action=getChannel&format=json");
		//Log.d(LOG_TAG, jsonChannels.toString());
		
		JSONArray nameArray = jsonChannels.names();
		JSONArray valArray = jsonChannels.toJSONArray(nameArray);
		
		// PUT JSON OBJECT IN HASHMAP
		HashMap<Integer,JSONObject> channelMap = new HashMap();
		for(int i = 0;i<valArray.length();i++) {
			int myChannelNumber = nameArray.getInt(i);
			JSONObject myChannel = valArray.getJSONObject(i);
			channelMap.put(myChannelNumber, myChannel);
		}
		//Log.i("JSON", map.toString());
		
		// ORDER ITEMS BY CHAINE_NUM
		for (int j=1; j<=channelMap.size(); j++) {
			if (channelMap.containsKey(j) == true) {
				JSONObject obj = (JSONObject)channelMap.get(j);
				Log.i("JSON",  "channel num="+j+" titre = "+obj.getString("titre"));
				
				/* ////////// TRACE RESULT (WITHOUT CHANNEL 7) ////////////////////////////
				08-26 16:19:53.772: INFO/augmentedTV(12937): channel num=1 titre= Tf1
				08-26 16:19:53.772: INFO/augmentedTV(12937): channel num=2 titre= France2
				08-26 16:19:53.772: INFO/augmentedTV(12937): channel num=3 titre= France3
				08-26 16:19:53.772: INFO/augmentedTV(12937): channel num=4 titre= Canalplus
				08-26 16:19:53.772: INFO/augmentedTV(12937): channel num=5 titre= France5
				08-26 16:19:53.772: INFO/augmentedTV(12937): channel num=6 titre= M6
				08-26 16:19:53.772: INFO/augmentedTV(12937): channel num=8 titre= Direct8
				08-26 16:19:53.772: INFO/augmentedTV(12937): channel num=9 titre= W9
				////////////////////////////////////////////////////////////////////// */

			}
		}
	}
	catch (JSONException e) {
		Log.e(LOG_TAG, "There was a Json parsing based error", e);  
	}
}



// CONVERT HTTP STREAM TO STRING //
private static String convertStreamToString(InputStream is) {
	BufferedReader reader = new BufferedReader(new InputStreamReader(is));
	StringBuilder sb = new StringBuilder();
	String line = null;
	try {
		while ((line = reader.readLine()) != null) {
			sb.append(line + "\n");
		}
	}
	catch (IOException e) { e.printStackTrace(); }
	finally {
		try { is.close(); }
		catch (IOException e) { e.printStackTrace(); }
	}
	return sb.toString();
}

// GET HTTP AND CONVERT TO JSON OBJECT //
public JSONObject getHttpJson(String url) {
	JSONObject json = null;
	String result = getHttp(url);
	try {
		json = new JSONObject(result);
	}
	catch (JSONException e) {
		Log.e(LOG_TAG, "There was a Json parsing based error", e);  
	}
	return json;
}

// GET HTTP (STREAM) //
public String getHttp(String url) {

	Log.d(LOG_TAG, "getHttp : "+url);
	
	String result = "";
	HttpClient httpclient = new DefaultHttpClient();
	HttpGet httpget = new HttpGet(url); 
	HttpResponse response;

	try {
		response = httpclient.execute(httpget);
		//Log.i(LOG_TAG, response.getStatusLine().toString());
		HttpEntity entity  = response.getEntity();
		if (entity != null) {
			InputStream instream = entity.getContent();
			result = convertStreamToString(instream);
			Log.i(LOG_TAG, result);
			instream.close();
		}
	}
	catch (ClientProtocolException e) {
		Log.e(LOG_TAG, "There was a protocol based error", e);  
	}
	catch (IOException e) {
		 Log.e(LOG_TAG, "There was an IO Stream related error", e);  
	}
	return result;
}


```