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

#import "ViewController.h"
#import "SecondViewController.h"
#import "ThirdViewController.h"

@interface ViewController ()
{
    UITextField *textPhoneNumber;
}

@property (nonatomic,retain) IBOutlet UITextField *textPhoneNumber;


@end

@implementation ViewController
@synthesize textPhoneNumber;

-(IBAction) onBindUserIdentifierClicked:(id)sender
{
    if([[textPhoneNumber text] isEqualToString:@""])
    {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"提示" message:@"输入需要为手机号" delegate:self cancelButtonTitle:@"确定" otherButtonTitles:nil, nil];
        [alert show];
    }
    else
    {
        [UMSAgent bindUserIdentifier:[textPhoneNumber text]];
        [textPhoneNumber resignFirstResponder];
    }
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    [UMSAgent checkUpdate];
}

- (void)throwNSException
{
    NSException *e = [NSException exceptionWithName:@"Null reference" reason:@"UIKit.framework.Exception" userInfo:nil];
    @throw e;
} 

-(IBAction) login
{
    [UMSAgent postEvent:@"login" label:@"Login" acc:666];
    [self throwNSException];
}

-(IBAction) register
{
    [UMSAgent postEvent:@"register" label:@"Login" acc:888];
    [UMSAgent postEventJSON:@"registerjson" json:@"{\"username\":\"sdktest\",\"telephone\":\"13815898257\"}"];
}

-(IBAction) gotToThirdView
{
    ThirdViewController *thirdViewController = [[ThirdViewController alloc] init];
    [self presentViewController:thirdViewController animated:YES completion:nil];
}


-(IBAction) goToSecondView
{
    SecondViewController *secondViewController = [[SecondViewController alloc] init];
    [self presentModalViewController:secondViewController animated:YES];
}

-(IBAction) tag
{
    [UMSAgent postTag:@"ios tag"];
}

-(void)viewWillAppear:(BOOL)animated
{
    [UMSAgent startTracPage:@"第一页"];
}

-(void)viewWillDisappear:(BOOL)animated
{
    [UMSAgent endTracPage:@"第一页"];
}



- (void)viewDidUnload	
{
    [super viewDidUnload];
    // Release any retained subviews of the main view.
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
    return (interfaceOrientation != UIInterfaceOrientationPortraitUpsideDown);
}


@end
