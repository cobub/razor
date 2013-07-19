/**
 * Cobub Razor
 *
 * An open source analytics windows phone sdk for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 0.1
 * @filesource
 */

using System;
using System.Net;
using System.Text;
using System.IO;
using System.Windows.Threading;
using UMSAgent.Common;
using UMSAgent.CallBcak;
using UMSAgent.MyObject;

namespace UMSAgent.Common
{
    
    public class Post
    {
        public delegate void stateChangedHandler(int type,string msg, object obj);
        public event stateChangedHandler stateChanged;
        public string my_url;
        public string message;
        public object obj;
        public string ret;
        public int type;
        
       
        public Post(int type, object obj)
        {
            this.type = type;
            this.obj = obj;
            getPostInfo(type, obj);
        
        }

        private void getPostInfo(int type,object data)
        {
            Obj2Json o = new Obj2Json();
            message = o.obj2jsonstr(data,type);
           
        }
        
        public  void sendData(string url)
        {
            /*
            var request = HttpWebRequest.Create(url);
            var result = (IAsyncResult)request.BeginGetResponse(ResponseCallback, request);
             */
            HttpWebRequest myRequest = (HttpWebRequest)WebRequest.Create(url);
            myRequest.Method = "POST";
            myRequest.ContentType = "application/x-www-form-urlencoded";
            myRequest.BeginGetRequestStream(new AsyncCallback(GetRequestStreamCallback), myRequest);
            
        }
       

        private  void GetRequestStreamCallback(IAsyncResult asynchronousResult)
        {

            HttpWebRequest request = (HttpWebRequest)asynchronousResult.AsyncState;
           

            System.IO.Stream postStream = request.EndGetRequestStream(asynchronousResult);

            string parametersString = "content="+this.message;
            
           // DebugTool.Log("post data:" + message);
            byte[] byteArray = System.Text.Encoding.UTF8.GetBytes(parametersString);
            
        
            // Write to the request stream.

            postStream.Write(byteArray, 0, parametersString.Length);

            postStream.Close();

            // Start the asynchronous operation to get the response
            try
            {
                request.BeginGetResponse(new AsyncCallback(GetResponseCallback), request);

            }
            catch (Exception e)
            {
                DebugTool.Log(e.InnerException.Message);
            }


        }

        private  void GetResponseCallback(IAsyncResult asynchronousResult)
        {

            HttpWebRequest request = (HttpWebRequest)asynchronousResult.AsyncState;

            HttpWebResponse response = null;
            try
            {
                response = (HttpWebResponse)request.EndGetResponse(asynchronousResult);
            }
            catch (Exception e)
            {
                DebugTool.Log(e.InnerException.Message);
            }
            if (response == null)
            {
                CommonRet errorRet = new CommonRet();
                errorRet.flag = "-100";
                errorRet.msg = "server is not founded.";
                ret = UmsJson.Serialize(errorRet);
                DebugTool.Log(ret);
                stateChanged(type, ret, obj);
                return;
            }
               

            Stream streamResponse = response.GetResponseStream();

            StreamReader streamRead = new StreamReader(streamResponse);

            string responseString = streamRead.ReadToEnd();

            // Close the stream object

            streamResponse.Close();

            streamRead.Close();
           
            ret = responseString;
            
            stateChanged(type, ret, obj);
        }

    }
}
