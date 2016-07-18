package com.wbtech.ums;

import java.io.IOException;
import java.net.Socket;
import java.net.UnknownHostException;
import java.security.KeyManagementException;
import java.security.KeyStore;
import java.security.KeyStoreException;
import java.security.NoSuchAlgorithmException;
import java.security.UnrecoverableKeyException;
import java.security.cert.CertificateException;
import java.security.cert.X509Certificate;

import javax.net.ssl.SSLContext;
import javax.net.ssl.TrustManager;
import javax.net.ssl.X509TrustManager;

import org.apache.http.conn.ssl.SSLSocketFactory;

import com.wbtech.ums.UmsConstants;

public class CposSSLSocketFactory extends SSLSocketFactory {
    SSLContext sslContext = SSLContext.getInstance("TLS");

    public CposSSLSocketFactory() throws NoSuchAlgorithmException,
            KeyManagementException, KeyStoreException,
            UnrecoverableKeyException {
        super((KeyStore) null);

        TrustManager tm = new X509TrustManager() {
            public void checkClientTrusted(X509Certificate[] chain,
                    String authType) throws CertificateException {

            }

            public void checkServerTrusted(X509Certificate[] chain,
                    String authType) throws CertificateException {

                for (X509Certificate x : chain) {
                    CobubLog.d(UmsConstants.LOG_TAG,CposSSLSocketFactory.class,"server dn is:"+ x.getSubjectDN().toString());
                    if (x.getSubjectDN().toString()
                            .equalsIgnoreCase(UmsConstants.SDK_HTTPS_DN)) {
                        return;
                    } else {
                        throw new CertificateException(
                                "illegal DN, reject the connection");
                    }
                }
            }

            public X509Certificate[] getAcceptedIssuers() {
                return null;
            }
        };
        setHostnameVerifier(ALLOW_ALL_HOSTNAME_VERIFIER);
        sslContext.init(null, new TrustManager[] { tm }, null);
    }

    @Override
    public Socket createSocket(Socket socket, String host, int port,
            boolean autoClose) throws IOException, UnknownHostException {
        return sslContext.getSocketFactory().createSocket(socket, host, port,
                autoClose);
    }

    @Override
    public Socket createSocket() throws IOException {
        return sslContext.getSocketFactory().createSocket();
    }

}
