http://developer.android.com/guide/tutorials/hello-world.html

# Eclipse #

http://www.eclipse.org/downloads/

http://developer.android.com/intl/fr/guide/developing/eclipse-adt.html

Ctrl-Shift-O (Cmd-Shift-O, on Mac). This is an Eclipse shortcut that identifies missing packages based on your code and adds them for you

# Others ressources #

http://www.insideandroid.fr/post/2009/04/16/Tutoriel%3A-Comment-utiliser-une-SDCard-sur-l-emulateur-Android

http://mobiforge.com/developing/story/sms-messaging-android

http://stackoverflow.com/questions/tagged/android

http://gwadanina.net/rss/author/Guy

http://www.frandroid.com

http://code.google.com/p/apps-for-android

# Android SDK example #

NOTIFICATION

```
Button button = (Button) findViewById(R.id.short_notify);
button.setOnClickListener(new Button.OnClickListener() {
    public void onClick(View v) {
        // Note also that we use the version of makeText that takes a resource id (R.string.short_notification_text).  There is also
        // a version that takes a CharSequence if you must construct the text yourself.
        Toast.makeText(NotifyWithText.this, R.string.short_notification_text,
            Toast.LENGTH_SHORT).show();
    }
});
```

UNITES

```
px
    Pixels - corresponds to actual pixels on the screen.
in
    Inches - based on the physical size of the screen.
mm
    Millimeters - based on the physical size of the screen.
pt
    Points - 1/72 of an inch based on the physical size of the screen.
dp
    Density-independent Pixels - an abstract unit that is based on the physical density of the screen. These units are relative to a 160 dpi screen, so one dp is one pixel on a 160 dpi screen. The ratio of dp-to-pixel will change with the screen density, but not necessarily in direct proportion. Note: The compiler accepts both "dip" and "dp", though "dp" is more consistent with "sp".
sp
    Scale-independent Pixels - this is like the dp unit, but it is also scaled by the user's font size preference. It is recommend you use this unit when specifying font sizes, so they will be adjusted for both the screen density and user's preference. 

```

LAYOUT

```

FrameLayout  	Layout that acts as a view frame to display a single object.
Gallery 	A horizontal scrolling display of images, from a bound list.
GridView 	Displays a scrolling grid of m columns and n rows.
LinearLayout 	A layout that organizes its children into a single horizontal or vertical row. It creates a scrollbar if the length of the window exceeds the length of the screen.
ListView 	Displays a scrolling single column list.
RelativeLayout 	Enables you to specify the location of child objects relative to each other (child A to the left of child B) or to the parent (aligned to the top of the parent).
ScrollView 	A vertically scrolling column of elements.
Spinner 	Displays a single item at a time from a bound list, inside a one-row textbox. Rather like a one-row listbox that can scroll either horizontally or vertically.
SurfaceView 	Provides direct access to a dedicated drawing surface. It can hold child views layered on top of the surface, but is intended for applications that need to draw pixels, rather than using widgets.
TabHost 	Provides a tab selection list that monitors clicks and enables the application to change the screen whenever a tab is clicked.
TableLayout 	A tabular layout with an arbitrary number of rows and columns, each cell holding the widget of your choice. The rows resize to fit the largest column. The cell borders are not visible.
ViewFlipper 	A list that displays one item at a time, inside a one-row textbox. It can be set to swap items at timed intervals, like a slide show.
ViewSwitcher 	Same as ViewFlipper. 

```
http://blog.pocketjourney.com/2008/04/04/tutorial-custom-media-streaming-for-androids-mediaplayer/

```
MEDIA PLAYER
```

http://developerlife.com/tutorials/?p=369

```
@Override
public void onCreate(Bundle icicle) {
  super.onCreate(icicle);
  try {

    Log.i(Global.TAG, "System.property(user.dir)=" + System.getProperty("user.dir"));

    // download an image from the web... in the background
    {
      Runnable getImage = new Runnable() {
        public void run() {
          // get the image from http://developerlife.com/theblog/wp-content/uploads/2007/11/news-thumb.png
          // save it here (user.dir/FILENAME)
          // file is saved here on emulator - /data/data/com.developerlife/files/file.png
          try {
            Log.i(Global.TAG, "MainDriver: trying to download and save PNG file to user.dir");
            HttpClient client = new HttpClient();
            GetMethod get = new GetMethod("http://developerlife.com/theblog/wp-content/uploads/2007/11/news-thumb.png");
            client.executeMethod(get);
            byte[] bRay = get.getResponseBody();

            FileOutputStream fos = openFileOutput(Global.FILENAME, Activity.MODE_WORLD_WRITEABLE);
            fos.write(bRay);
            fos.flush();
            fos.close();
            Log.i(Global.TAG, "MainDriver: successfully downloaded PNG file to user.dir");
          }
          catch (Exception e) {
            Log.e(Global.TAG, "MainDriver: could not download and save PNG file", e);
          }

        }
      };
      new Thread(getImage).start();
    }

  }
  catch (Exception e) {
    Log.e(Global.TAG, "ui creation problem", e);
  }

}

```

## STYLE & DRAWABLE ##

AndroidManifest.xml

```
<activity android:name=".MyThemeTest" android:label="@string/app_name" android:theme="@style/MyTheme">
```

res/values/styles.xml

```
<?xml version="1.0" encoding="utf-8"?>
<resources>
	<style name="MyTheme" parent="android:style/Theme.Light.NoTitleBar">
		<item name="android:windowBackground">@drawable/blue_filled_box</item>
	</style>
	<style name="titreList">
		<item name="android:textSize">14px</item>
		<item name="android:textColor">#999999</item>
		<item name="android:background">@drawable/pattern</item>
	</style>

</resources>
```

res/drawable/blue\_filled\_box.xml

```
<?xml version="1.0" encoding="utf-8"?>
<shape xmlns:android="http://schemas.android.com/apk/res/android">
    <solid android:color="#0000FF"/>
    <stroke android:width="3dp" color="0000FF80"/>
    <corners android:radius="3dp"/>
    <padding android:left="10dp" android:top="10dp"
        android:right="10dp" android:bottom="10dp" />
</shape>
```

Activitie Thème

```
setTheme(R.style.bytelTheme);
setTheme(android.R.style.Theme_Translucent);
```

VIDEO STREAM

```

import android.app.Activity;
import android.media.MediaPlayer;
import android.os.Bundle;
import android.view.SurfaceHolder;
import android.view.View;
import android.widget.*;

public class playerActivity extends Activity 
{
Button b;
VideoView preview;
SurfaceHolder holder;
MediaPlayer mp;

 private String path = "/data/data/payoda.android/funny.mp4";

//private String path = "http://www.daily3gp.com/vids/3.3gp";

public void onCreate(Bundle savedInstanceState) 
{
        super.onCreate(savedInstanceState);
        setContentView(R.layout.main);
        preview=(VideoView)findViewById(R.id.surface);
        holder=preview.getHolder();
        b=(Button)findViewById(R.id.cmd_play);
        b.setOnClickListener(new View.OnClickListener()
        {
        public void onClick(View v)
        {
                try
                {
                        mp=new MediaPlayer(); 
                    mp.setDataSource(path);
                    mp.setScreenOnWhilePlaying(true);
                    mp.setDisplay(holder);
                    mp.prepare();
                    mp.start();
                }
                catch(Exception e)
                {

                }
        }
        });
}
}

```

WEBVIEW CAPTURE TO BITMAP

```
public void onCreate(Bundle savedInstanceState) {
	super.onCreate(savedInstanceState);

	WebView w = new WebView(this);
	w.getSettings().setJavaScriptEnabled(true);
	w.setWebViewClient(new WebViewClient() {
		public void onPageFinished(WebView webview, String url) {
			Picture picture = webview.capturePicture();
			Bitmap b = Bitmap.createBitmap(picture.getWidth(), picture.getHeight(), Bitmap.Config.ARGB_8888);
			Canvas c = new Canvas(b);
			picture.draw(c);
		}
	});

	setContentView(w);

	w.loadUrl("http://www.yahoo.com"); // yes
	// w.loadUrl("http://search.yahoo.com/search?p=android"); // usually not???
	// w.loadUrl("http://www.yahoo.com?foo=bar"); // nope
	// w.loadUrl("http://www.google.com"); // yep
	// w.loadUrl("http://www.google.com?q=android"); // yep
}
```

CALL SMS

```

Intent sendIntent = new Intent(Intent.ACTION_VIEW);
sendIntent.putExtra("sms_body", titre); // address=0139154205
sendIntent.setType("vnd.android-dir/mms-sms"); 
startActivity(sendIntent);

```

CALL Email

```

Intent sendIntent = new Intent(Intent.ACTION_SEND);
sendIntent.putExtra(Intent.EXTRA_SUBJECT, "Je te recommande une emission");
sendIntent.putExtra(Intent.EXTRA_TEXT, emailTexte);
sendIntent.setType("message/rfc822");
startActivity(Intent.createChooser(sendIntent, "Choisir votre client email :"));

```

CALL EMAIL 2

```

Button sendBtn = (Button) findViewById(R.id.send_btn_id);
        sendBtn.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v)
            {
            	Intent emailIntent = new Intent(android.content.Intent.ACTION_SEND);
            	emailIntent.setType("text/html");
            	emailIntent.putExtra(android.content.Intent.EXTRA_EMAIL,
                  new String[]{ sendToAddress } );

            	emailIntent.putExtra(android.content.Intent.EXTRA_SUBJECT, subject );
            	emailIntent.putExtra(android.content.Intent.EXTRA_TEXT, msg );

            	hideKeyboard();

            	NoteToMe.this.startActivity(emailIntent);
            	NoteToMe.this.finish();
            }
        });


```

CALL VIDEO

```

Intent sendIntent = new Intent(Intent.ACTION_VIEW);
sendIntent.setDataAndType(Uri.parse("myvideo.3gp"), "video/*");
startActivity(sendIntent);

```

KILLER APP

```

android.os.Process.killProcess(android.os.Process.myPid())  
System.exit(0);
ActivityManager.restartPackage(packageName);

<activity android:clearTaskOnLaunch="true" ... >

```

COPY CLIPBOARD

```

ClipboardManager clipboard = (ClipboardManager) getSystemService(CLIPBOARD_SERVICE);
clipboard.setText(twitterTitre);
Toast.makeText(myApp, "Vous pouvez coller les infos de l'émission (long clic + paste)", Toast.LENGTH_LONG).show();

```

SIMPLE ANIMATION

```

@Override
public void onCreate(Bundle savedInstanceState) {
	super.onCreate(savedInstanceState);
	setContentView(R.layout.animation_1);
	View loginButton = findViewById(R.id.login);
	loginButton.setOnClickListener(this);
}

public void onClick(View v) {
	Animation shake = AnimationUtils.loadAnimation(this, R.anim.shake);
	findViewById(R.id.pw).startAnimation(shake);
}

```

DO IT IN BACKGROUND
http://developerlife.com/tutorials/?p=290

```
public void execute(NetworkActivity activity) {

  _activity = activity;

  uid = activity.ttfUserid.getText().toString();
  pwd = activity.ttfPassword.getText().toString();

  // allows non-"edt" thread to be re-inserted into the "edt" queue
  final Handler uiThreadCallback = new Handler();

  // performs rendering in the "edt" thread, after background operation is complete
  final Runnable runInUIThread = new Runnable() {
    public void run() {
      _showInUI(); // UI TREAT
    }
  };

  new Thread() {
    @Override public void run() {
      _doInBackgroundPost();  // PROCESS TREAT
      uiThreadCallback.post(runInUIThread);
    }
  }.start();

  Toast.makeText(_activity,
                 "Getting data from servlet",
                 Toast.LENGTH_LONG).show();
}

```

FETCH IMAGE BY HTTP JSON OBJECT

```

 Pre-load the image then start the animation
JSONObject myCurrentEmission = null;
try {
	GetJsonHttp jsonObj = new GetJsonHttp(SITE_URL+"?action=current&current="+(position+1));

	myCurrentEmission = jsonObj.json.toJSONArray(jsonObj.json.names()).getJSONObject(0);

	URL myFileUrl = null;
	myFileUrl = new URL(myCurrentEmission.getString("icone_emission"));
	if (myFileUrl != null) {
		HttpURLConnection conn = (HttpURLConnection) myFileUrl.openConnection();
		conn.setDoInput(true);
		conn.connect();
		mVideoView.setImageBitmap(BitmapFactory.decodeStream(conn.getInputStream()));
		conn.disconnect();
	}
	else mVideoView.setImageResource(R.drawable.accueil_intro);
}
catch (MalformedURLException e) {
	Log.e(LOG_TAG, "onItemClick() There was a malformed url based error", e);
}
catch (JSONException e) {
	Log.e(LOG_TAG, "getView() There was a Json parsing based error", e);  
}
catch (IOException e) {
	Log.e(LOG_TAG, "getView() There was a url access to image based error", e);  
}

```

TIMER

```

// WE START VIDEO AFTER 3D ROTATION
new CountDownTimer(1000, 500) { // 1 seconde (millisecondes), 2 fois (500 millisecondes)
	public void onTick(long millisUntilFinished) {
		mTextField.setText("seconds remaining: " + millisUntilFinished / 1000);
	}
	public void onFinish() {
		//...
	}
}.start();

```

ALERTS

```

new AlertDialog.Builder(myApp)  
	.setTitle("Information")  
	.setMessage(message)  
	.setPositiveButton(android.R.string.ok, null)  
	.setCancelable(false)  
	.create()  
	.show(); 

new AlertDialog.Builder(myApp)  
	.setTitle("JavaScript dialog")  
	.setMessage(message)  
	.setPositiveButton(android.R.string.ok,  
		new AlertDialog.OnClickListener() {  
			public void onClick(DialogInterface dialog, int which) {  
				result.confirm();  
			}
		})
	.setCancelable(false)  
	.create()  
	.show();
```

RESIZE IMAGE

```

public static Drawable resizeImage(Context ctx, int resId, int w, int h) {

	// load the original Bitmap
	Bitmap BitmapOrg = BitmapFactory.decodeResource(ctx.getResources(), resId);

	int width = BitmapOrg.getWidth();
	int height = BitmapOrg.getHeight();
	int newWidth = w;
	int newHeight = h;

	// calculate the scale
	float scaleWidth = ((float) newWidth) / width;
	float scaleHeight = ((float) newHeight) / height;

	// create a matrix for the manipulation
	Matrix matrix = new Matrix();
	// resize the Bitmap
	matrix.postScale(scaleWidth, scaleHeight);
	// if you want to rotate the Bitmap
	// matrix.postRotate(45);

	// recreate the new Bitmap
	Bitmap resizedBitmap = Bitmap.createBitmap(BitmapOrg, 0, 0, width, height, matrix, true);

	// make a Drawable from Bitmap to allow to set the Bitmap
	// to the ImageView, ImageButton or what ever
	return new BitmapDrawable(resizedBitmap);
}

```

CATCH ALL ERROR

```

protected void onCreate(Bundle savedInstanceState) {
	/* ... */
	Thread.setDefaultUncaughtExceptionHandler(onBlooey);
}

private Thread.UncaughtExceptionHandler onBlooey = new Thread.UncaughtExceptionHandler() {
	public void uncaughtException(Thread thread, Throwable ex) {
		Log.e(LOG_TAG, "MOONWALKER IS DOWN, BUT WITHOUT BLUESCREEN OF DEATH", ex);
		new AlertDialog.Builder(getBaseContext())  
			.setTitle("Information")  
			.setMessage("MOONWALKER IS DOWN :-/")  
			.setPositiveButton(android.R.string.ok, null)  
			.setCancelable(false)  
			.create()  
			.show(); 
		System.exit(0);
	}
};

```

COPY

```

ClipboardManager clipboard = (ClipboardManager) getSystemService(CLIPBOARD_SERVICE);
clipboard.setText(twitterTitre);
Toast.makeText(myApp, "Vous pouvez coller les infos de l'émission (long clic + paste)", Toast.LENGTH_LONG).show();

```

WINDOW STYLE

```

// J'ai pas reussi a avoir un arriere plan transparent... :/
WindowManager.LayoutParams lpWindow = new WindowManager.LayoutParams(); 
lpWindow.flags = WindowManager.LayoutParams.FLAG_DIM_BEHIND; 
lpWindow.dimAmount = 0f;
//lpWindow.alpha = 0.3f;
//lpWindow.gravity = Gravity.TOP;
lpWindow.windowAnimations = android.R.anim.fade_in | android.R.anim.fade_out; 
mProgressDialog.getWindow().setAttributes(lpWindow);

```