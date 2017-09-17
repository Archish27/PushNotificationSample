package com.archish.pushnotificationsample;

import android.util.Log;

import com.google.firebase.iid.FirebaseInstanceId;
import com.google.firebase.iid.FirebaseInstanceIdService;


public class MyFirebaseInstanceIDService extends FirebaseInstanceIdService {

    private static final String TAG = "MyFirebaseIIDService";

    @Override
    public void onTokenRefresh() {
        String refreshedToken = FirebaseInstanceId.getInstance().getToken();
        Log.d(TAG, "Refreshed token: " + refreshedToken);
        storeToken(refreshedToken);
        /**
         * Note : This token will be generated new again & again every hour since the token time expires
         * & also Firebase can set 1000 users ata a time if the number of users exceeds then you will not receive
         * any notifications to resolve this issue divide users according to group so that firebase can serve notification
         * less than 1000 users & so on.
         **/
    }


    private void storeToken(String token) {
        //saving the token on shared preferences
    }


}