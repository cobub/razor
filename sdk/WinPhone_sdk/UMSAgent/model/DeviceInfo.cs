using System;
using System.Net;
using UMSAgent.Common;
using Microsoft.Phone.Info;


namespace UMSAgent.Model
{
    internal class DeviceInfo:UMSTime
    {
        public static volatile DeviceInfo deviceInfo;
        private string UMS_KEY_DEVICE_ID;
        private string UMS_KEY_OS;
        
        //
        public DeviceInfo():base(0)
            
        {
            this.UMS_KEY_DEVICE_ID = "device_id";
            this.UMS_KEY_OS = "os";
            try
            {
                this.initDeviceInfo();
            }
            catch (Exception e)
            {
                DebugTool.Log("init deviceInfo error:", e);
            }
            
            
        }
        //初始化
        public void initDeviceInfo()
        {
           
            base.put(this.UMS_KEY_DEVICE_ID, this.getDeviceID());
           
            base.put(this.UMS_KEY_OS, Constants.operateSystem);
            base.put("platform", "Windows Phone");
            base.put("appkey", "windowsphoneappkeytest");
            base.put("resolution", "320*480");

        }
        //获取设备信息实例
        public static DeviceInfo getInstance()
        {
            //if (deviceInfo == null)
            //{
            //    lock (lockHelper)
            //    {
            //        if (deviceInfo == null)
            //        {
            //            deviceInfo = new DeviceInfo();
            //        }
            //    }
            //}
            DebugTool.Log("get device insatance");
            if (deviceInfo == null)
                deviceInfo = new DeviceInfo();
            return deviceInfo;
        }
        //获取设备id
        private string getDeviceID()
        {
            try
            {
                byte[] input = (byte[])DeviceExtendedProperties.GetValue("DeviceUniqueId");
                if ((input != null) && (input.Length > 0))
                {
                    return MD5Core.GetHashString(input);
                }
            }
            catch (Exception exception)
            {
                DebugTool.Log(exception);
            }
            
            return "0000000000";
        }
    }
}
