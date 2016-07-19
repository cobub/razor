/**
 * Cobub Razor
 *
 * An open source analytics iphone sdk for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 0.1
 * @filesource
 */

#import <Foundation/Foundation.h>
#import <UIKit/UIKit.h>

typedef enum {
    BATCH = 0,          //Send Data When Start
    REALTIME = 1       //RealTime Send Policy
    //INTERVAL = 2
} ReportPolicy;

@interface UMSAgent : NSObject<UIAlertViewDelegate>
{
    BOOL isLogEnabled;
}

@property (nonatomic) BOOL isLogEnabled;
/**
 *  向cobub_cloud注册第三方应用
 *
 *  @param appKey 开发者Key
 */
#pragma mark ---appkey
+ (void)startWithAppKey:(NSString*)appKey serverURL:(NSString *)serverURL;
+ (void)startWithAppKey:(NSString*)appKey ReportPolicy:(ReportPolicy)policy serverURL:(NSString*)serverURL;
/**
 *  保存错误日志
 *
 *  @param stackTrace 错误栈信息
 */
+ (void)saveErrorLog:(NSString *)stackTrace;
/**
 *  发送错误日志
 *
 *  @param stackTrace 错误栈信息
 */
+ (void)postErrorLog:(NSString*)stackTrace;
/**
 *  发送单个事件
 *
 *  @param event_id 事件ID
 */
+ (void)postEvent:(NSString *)event_id;
/**
 *  发送单个事件,可以同时发送一个对应的标签
 *
 *  @param event_id 事件ID
 *  @param label 标签
 */
+ (void)postEvent:(NSString *)event_id label:(NSString *)label;
/**
 *  发送多个相同事件，多个高频率出现的事件，可采用此方法降低网络流量。
 *
 *  @param event_id 事件ID
 *  @param acc      计数器
 */
+ (void)postEvent:(NSString *)event_id acc:(NSInteger)acc;
/**
 *  发送多个事件，并含有相应的标签
 *
 *  @param event_id 事件ID
 *  @param label    标签
 *  @param acc      计数器
 */
+ (void)postEvent:(NSString *)event_id label:(NSString *)label acc:(NSInteger)acc;
/**
 *  发送默认的系统事件，事件ID需要实现在系统配置好,ID默认为:default_maadmin_event
 *
 *  @param label 标签
 *  @param acc   计数器
 */
+ (void)postGenericEvent:(NSString *)label acc:(NSInteger)acc;
/**
 *  发送JSON数据，JSON数据格式为{"a":"avalue","b":"bvalue"....}
 *
 *  @param event_id 事件ID
 *  @param jsonStr  json数据
 */
+ (void)postEventJSON:(NSString*)event_id json:(NSString*)jsonStr;

/**
 *  绑定Tags
 *  用户可以自定义设置tag信息，并发送至后台
 *
 *  @param tag 标签
 */
+ (void)postTag:(NSString *)tag;
/**
 *  页面统计
 *
 *  @param page_name 页面名称
 */
+ (void)tracePage:(NSString*)page_name;
/**
 *  开始页面统计
 *
 *  @param page_name 页面名称
 */
+ (void)startTracPage:(NSString*)page_name;
/**
 *  结束页面统计
 *
 *  @param page_name 页面名称
 */
+ (void)endTracPage:(NSString*)page_name;

/**
 *  绑定用户ID
 *
 *  @param userid 用户ID
 */
+ (void)bindUserIdentifier:(NSString *)userid;
/**
 *  设置发送间隔时间
 *
 *  @param interval 间隔时长
 */
+ (void)setPostIntervalMillis:(int)interval;
/**
 *  设置GPS
 *
 *  @param latitude 纬度
 *  @param longitude 经度
 */
+ (void)setGPSLocation:(double)latitude longitude:(double)longitude;
/**
 *  使用自定义参数
 *
 *  @param key 参数key
 *
 *  @return 参数值
 */
+ (NSString *)getConfigParam:(NSString*)key;
/**
 *  更新在线参数
 */
+ (void)updateOnlineParams;

/**
 *  当前设备是否越狱
 *
 *  @return 是返回YES,否返回NO
 */
+ (BOOL)isJailbroken;
/**
 *  在线配置(如果希望使用在线配置，请在startWithAppKey调用之前调用setOnLineConfig)
 *
 *  @param isOnlineConfig 是否在线配置
 */
+ (void)setOnLineConfig:(BOOL)isOnlineConfig;
/**
 *  是否打开调试日志
 *
 *  @param isLogEnabled 是返回YES,否返回NO
 */
+ (void)setIsLogEnabled:(BOOL)isLogEnabled;
/**
 *  是否在非wifi状态下仍然检查更新
 *
 *  @param isUnderWIFI 是否在wifi下
 */
+ (void)setUpdateOnlyWifi:(BOOL)isUnderWIFI;
/**
 *  手动设置DeviceID
 *  手动设置设备识别号。但是一定要在实例化UMSAgent之前调用手动设置方法，否则会导致数据不一致
 *
 *  @param deviceID DeviceID
 */
+ (void)setDeviceID: (NSString*)deviceID;

//Auto update
/**
 *  自动更新(系统默认只在用户具备WIFI的条件下才进行更新检查)
 */
+ (void)checkUpdate;

//For internal used
+ (NSString*)getUMSUDID;
/**
 *  获得用户ID
 *
 *  @return 返回用户ID
 */
+ (NSString*)getUserId;
/**
 *  获得SessionID
 *
 *  @return 返回SessionID
 */
+ (NSString*)getSessionId;
/**
 *  单例
 *
 *  @return 返回UMSAgent
 */
+ (UMSAgent*)getInstance;
/**
 *  获得设备名称
 *
 *  @return 返回设备名称
 */
- (NSString*)machineName;

//File Utils
/**
 *  获得文件路径
 *
 *  @param fileName 文件名
 *
 *  @return 返回文件路径
 */
+ (NSString*) getFilePath:(NSString*)fileName;
/**
 *  获得文件大小
 *
 *  @param filePath 文件路径
 *
 *  @return 返回文件大小
 */
+ (long long) fileSizeAtPath:(NSString*) filePath;
/**
 *  检查数据大小并保存
 *
 *  @param object   对象
 *  @param fileName 文件名
 */
+ (void) checkSizeAndSaveObject:(id)object ToFile:(NSString*)fileName;
/**
 *  移除文件
 *
 *  @param fileName 文件名
 */
+ (void) removeArchivedFile: (NSString*)fileName;
/**
 *  数据是否太大需要被移除
 *
 *  @param fileName 文件名
 *
 *  @return 是返回YES,否返回NO
 */
+ (BOOL) fileTooLargeNeedRemoval: (NSString*)fileName;
/**
 *  获取已保存的数据
 *
 *  @param fileName 文件名
 *
 *  @return 返回已保存的数据
 */
+ (NSData*) getArchivedLogFromFile: (NSString*)fileName;
/***
 //deprecated
 + (void)startTracPage:(NSString*)page_name __attribute__((deprecated));
 + (void)endTracPage:(NSString*)page_name __attribute__((deprecated));
 ***/
@end
