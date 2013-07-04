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

using System.IO;
using System.Runtime.Serialization.Json;
using System.Text;
using UMSAgent.MyObject;

namespace UMSAgent.Common
{
    public class Serializer
    {

        /// <summary>  
        /// Serialize object.  
        /// </summary>  
        /// <typeparam name="T">The Object type.</typeparam>  
        /// <param name="obj">The object to serialize.  
        /// <returns>The serialized object.</returns>  
        public static string WriteFromObject<t>(ErrorInfo obj)
        {
            MemoryStream ms = new MemoryStream();
            DataContractJsonSerializer ser = new DataContractJsonSerializer(typeof(ErrorInfo));
            ser.WriteObject(ms, obj);
            byte[] json = ms.ToArray();
            ms.Close();
            return Encoding.UTF8.GetString(json, 0, json.Length);
        }

        /// <summary>  
        /// Deserialize object.  
        /// </summary>  
        /// <typeparam name="T">The object type.</typeparam>  
        /// <param name="json">The serialized object.  
        /// <returns>The deserialized object.</returns>  
        public static ErrorInfo ReadToObject<t>(string json)
        {
            MemoryStream ms = new MemoryStream(Encoding.UTF8.GetBytes(json));
            DataContractJsonSerializer ser = new DataContractJsonSerializer(typeof(ErrorInfo));
            ErrorInfo obj = (ErrorInfo)ser.ReadObject(ms);
            ms.Close();
            return obj;
        }
    }
}