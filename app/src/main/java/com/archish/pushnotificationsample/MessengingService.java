package com.archish.pushnotificationsample;

import android.content.Intent;

import com.google.firebase.messaging.FirebaseMessagingService;
import com.google.firebase.messaging.RemoteMessage;

import org.json.JSONArray;
import org.json.JSONObject;

public class MessengingService extends FirebaseMessagingService {

    @Override
    public void onCreate() {
        super.onCreate();
    }

    static JSONArray user = null;


    @Override
    public void onMessageReceived(RemoteMessage remoteMessage) {
        super.onMessageReceived(remoteMessage);
        try {
            JSONObject json = new JSONObject(remoteMessage.getData().toString());
            sendPushNotification(json);
        } catch (Exception e) {
        }
    }


    private void sendPushNotification(JSONObject json) {
        //optionally we can display the json into log

        try {
            //getting the json data
            JSONObject data = json.getJSONObject("data");

            String status = data.getString("status");


            //creating MyNotificationManager object
            SMNotificationManager mNotificationManager = new SMNotificationManager(getApplicationContext());

            //creating an intent for the notification
            Intent messageIntent = new Intent(getApplicationContext(), MainActivity.class);
            messageIntent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);

            //if there is no image
            String id = data.getString("id");
            String title = data.getString("title");
            String message = data.getString("message");
            String date = data.getString("date");
            String imageUrl = data.getString("image");
            String audioUrl = data.getString("audio");
            String videoUrl = data.getString("video");
            mNotificationManager.showMessageNotification(id, title, message, date, "", videoUrl, audioUrl, imageUrl, status, messageIntent);

        } catch (Exception e) {

        }
    }

    /**
     * Issues a notification to inform the user that server has sent a message.
     */
//    private static void generateNotification(Context context, String message, String title, String status) {
//
//        int icon = R.drawable.ic_launcher;
//        long when = System.currentTimeMillis();
//        NotificationManager notificationManager = (NotificationManager)
//                context.getSystemService(Context.NOTIFICATION_SERVICE);
//        String title1 = context.getString(R.string.app_name);
//        Intent notificationIntent = null;
//
//        int notificationNo = 0;
//        String vibrateStringSM = "", vibrateStringS = "";
//        Uri ringtone = null;
//        SharedPreferences sharedPrefs = PreferenceManager.getDefaultSharedPreferences(context);
//        try {
//            long[] pattern = new long[0];
//
//            if (status.equals("1")) {
//                notificationNo = 1;
//                notificationIntent = new Intent(context, MainActivity.class);
//                notificationIntent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
//                vibrateStringSM = sharedPrefs.getString("vibrate_sm", "1");
//                ringtone = Uri.parse(sharedPrefs.getString("sm_notification_ringtone", Settings.System.DEFAULT_NOTIFICATION_URI.toString()));
//                switch (vibrateStringSM) {
//                    case "1":
//                        pattern = new long[]{1000, 1000, 1000, 1000};
//                        break;
//                    case "2":
//                        pattern = new long[]{500, 500, 500, 500};
//                        break;
//                    case "3":
//                        pattern = new long[]{300, 300, 300, 300};
//                        break;
//                    case "4":
//                        pattern = new long[]{0, 0, 0, 0};
//                        break;
//
//                }
//            }
//            if (status.equals("2")) {
//                title1 = title;
//                notificationNo = 2;
//                notificationIntent = new Intent(context, SadvicharActivity.class);
//                notificationIntent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
//                vibrateStringS = sharedPrefs.getString("vibrate_s", "1");
//                ringtone = Uri.parse(sharedPrefs.getString("s_notification_ringtone", Settings.System.DEFAULT_NOTIFICATION_URI.toString()));
//                switch (vibrateStringS) {
//                    case "1":
//                        pattern = new long[]{500, 500, 500, 500};
//                        break;
//                    case "2":
//                        pattern = new long[]{350, 350, 350, 350};
//                        break;
//                    case "3":
//                        pattern = new long[]{200, 200, 200, 200};
//                        break;
//                    case "4":
//                        pattern = new long[]{0, 0, 0, 0};
//                        break;
//                }
//
//            }
//            if (sharedPrefs.getBoolean("mute_all", false)) {
//                pattern = new long[]{0, 0, 0, 0};
//                ringtone = null;
//            }
//            PendingIntent intent =
//                    PendingIntent.getActivity(context, 0, notificationIntent, 0);
//
//
//            NotificationCompat.Builder builder = new NotificationCompat.Builder(context);
//            builder.setSmallIcon(R.drawable.ic_launcher)
//                    .setContentTitle(title1).setContentText(message).setWhen(when)
//                    .setContentIntent(intent)
//                    .setLargeIcon(BitmapFactory.decodeResource(context.getResources(), R.drawable.ic_launcher))
//                    .setAutoCancel(true)
//                    .setVibrate(pattern)
//                    .setSound(ringtone);
//
//
//            Notification notification = builder.getNotification();
//
//            notification.flags |= Notification.FLAG_AUTO_CANCEL;
//
//
//            Random rand = new Random();
//            int uniqueNo = rand.nextInt(5000) + 1;
//            notificationManager.notify(notificationNo, notification);
//
//            Log.d("GenerateSuccessful", "GenrateSuccess");
//
//        } catch (Exception ae) {
//            notificationIntent = new Intent(context, MainActivity.class);
//        }
//    }

//    private static void insertToDatabase(final String id) {
//
//        class SendPostReqAsyncTask extends AsyncTask<String, String, JSONObject> {
//            final String TAG_USER = "messagetable";
//
//            @Override
//            protected JSONObject doInBackground(String... params) {
//
//                String paramUsername = params[0];
//
//                System.out.println("paramUsername" + paramUsername);
//
//                // Create an intermediate to connect with the Internet
//                HttpClient httpClient = new DefaultHttpClient();
//
//                // Sending a GET request to the web page that we want
//                // Because of we are sending a GET request, we have to pass the values through the URL
//                HttpGet httpGet = new HttpGet(Config.url + "getdata.php?id=" + paramUsername);
//
//                try {
//                    // execute(); executes a request using the default context.
//                    // Then we assign the execution result to HttpResponse
//                    HttpResponse httpResponse = httpClient.execute(httpGet);
//                    InputStream inputStream = httpResponse.getEntity().getContent();
//
//                    // We have a byte stream. Next step is to convert it to a Character stream
//                    InputStreamReader inputStreamReader = new InputStreamReader(inputStream);
//
//                    // Then we have to wraps the existing reader (InputStreamReader) and buffer the input
//                    BufferedReader bufferedReader = new BufferedReader(inputStreamReader);
//
//                    StringBuilder stringBuilder = new StringBuilder();
//
//                    String bufferedStrChunk = null;
//
//                    while ((bufferedStrChunk = bufferedReader.readLine()) != null) {
//                        stringBuilder.append(bufferedStrChunk);
//                    }
//
//                    System.out.println("Returninge of doInBackground :" + stringBuilder.toString());
//
//                    // If the Username and Password match, it will return "working" as response
//                    // If the Username or Password wrong, it will return "invalid" as response
//
//
//                } catch (ClientProtocolException cpe) {
//                    System.out.println("Exceptionrates caz of httpResponse :" + cpe);
//                    cpe.printStackTrace();
//                } catch (IOException ioe) {
//                    System.out.println("Secondption generates caz of httpResponse :" + ioe);
//                    ioe.printStackTrace();
//                }
//                JSONParser jParser = new JSONParser();
//
//                // Getting JSON from URL
//                JSONObject json = jParser.getJSONFromUrl(url1 + "?id=" + id);
//                return json;
//
//            }
//
//            @Override
//            protected void onPostExecute(JSONObject json) {
//                try {
//                    // Getting JSON Array
//                    final String TAG_ID = "id";
//                    user = json.getJSONArray(TAG_USER);
//                    for (int i = 0; i < user.length(); i++) {
//                        JSONObject c = user.getJSONObject(i);
//                        String id = c.getString(TAG_ID);
//                        String title = c.getString("title");
//                        String message = c.getString("message");
//                        String date = c.getString("date");
//                        String image = c.getString("image");
//                        String video = c.getString("video");
//                        String audio = c.getString("audio");
//                        String status = c.getString("status");
//                        String imagelist = c.getString("imagelist");
//                        db.insertToMessage(id, title, message, date, image, video, audio, status, imagelist);
//                    }
//                    Log.d("Successfull Post", "Data");
//                    // Storing  JSON item in a Variable
//
//                } catch (Exception e) {
//                    e.printStackTrace();
//                }
//
//
//            }
//
//        }
//        SendPostReqAsyncTask sendPostReqAsyncTask = new SendPostReqAsyncTask();
//        sendPostReqAsyncTask.execute(id);
//    }

}
