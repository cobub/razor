package com.wbtech.ums;

import java.io.IOException;
import java.net.Socket;
import java.net.UnknownHostException;
import java.security.KeyStore;
import java.security.cert.CertificateException;
import java.security.cert.X509Certificate;

import javax.net.ssl.SSLContext;
import javax.net.ssl.TrustManager;
import javax.net.ssl.X509TrustManager;

import org.apache.http.conn.ssl.SSLSocketFactory;

import com.wbtech.ums.UmsConstants;

import android.util.Log;

public class SSLCustomSocketFactory extends SSLSocketFactory {
    private SSLContext mCtx;
    
    
    public class SSLTrustAllManager implements X509TrustManager {

        @Override
        public void checkClientTrusted(X509Certificate[] arg0, String arg1)
                throws CertificateException {
            
        }

        @Override
        public void checkServerTrusted(X509Certificate[] arg0, String arg1)
                throws CertificateException {
            
        }

        @Override
        public X509Certificate[] getAcceptedIssuers() {
            return null;
        }
        
    }
    public SSLCustomSocketFactory(KeyStore trustStore) throws Throwable {
        super(trustStore);
        try {
            mCtx = SSLContext.getInstance("TLS");
            mCtx.init(null, new TrustManager[]{new SSLTrustAllManager()}, null);
            setHostnameVerifier(SSLSocketFactory.ALLOW_ALL_HOSTNAME_VERIFIER);
        } catch(Exception e) {
            CobubLog.e(UmsConstants.LOG_TAG,e);
        }
    }

    
    @Override
    public Socket createSocket(Socket socket, String host, int port,
            boolean autoClose) throws IOException, UnknownHostException {
       return mCtx.getSocketFactory().createSocket(socket, host,port,autoClose);
    }

    
    @Override
    public Socket createSocket() throws IOException {
       return mCtx.getSocketFactory().createSocket();
    }


    public static SSLSocketFactory getSocketFactory() {
     
        try {         
            KeyStore trustStore = KeyStore.getInstance(KeyStore
                    .getDefaultType());
            trustStore.load(null,null);
         
            SSLSocketFactory factory = new SSLCustomSocketFactory(trustStore);
            return factory;
        } catch (Throwable e) {
            CobubLog.e(UmsConstants.LOG_TAG,SSLCustomSocketFactory.class, e.toString());
        } 
        return null;
    }
}