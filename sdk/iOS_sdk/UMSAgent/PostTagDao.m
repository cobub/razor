//
//  PostTagDao.m
//  UMSAgent
//
//  Created by admin on 13-4-27.
//
//

#import "PostTagDao.h"
#import "Global.h"
#import "NSDictionary_JSONExtensions.h"
#import "network.h"
@implementation PostTagDao
+(CommonReturn *)postTag:(NSString *)appkey tag:(Tag *)tag
{
    NSString* url = [NSString stringWithFormat:@"%@%@",[Global getBaseURL],@"/ums/postTag"];
    
    CommonReturn *ret = [[CommonReturn alloc] init];
    NSMutableDictionary *requestDictionary = [[NSMutableDictionary alloc] init];
    [requestDictionary setObject:tag.deviceid forKey:@"deviceid"];
    [requestDictionary setObject:tag.tags forKey:@"tags"];
    [requestDictionary setObject:tag.productkey forKey:@"productkey"];
    
    
    NSString *retString = [network SendData:url data:requestDictionary];
    
    NSError* error = nil;
    NSDictionary * retDictionary = [NSDictionary dictionaryWithJSONString:retString error:&error];
    ret.flag = [[retDictionary objectForKey:@"flag" ] intValue];
    ret.msg = [retDictionary objectForKey:@"msg"];
    return ret;
    
    
}

@end
