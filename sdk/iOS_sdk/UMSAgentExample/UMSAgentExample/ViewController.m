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
@interface ViewController ()

@end

@implementation ViewController

- (void)viewDidLoad
{
    [super viewDidLoad];
    [UMSAgent postTag:@"tag"];
    [UMSAgent postPushid:@"pushid"];
}

- (void)throwNSException
{
    NSException *e = [NSException exceptionWithName:@"Null reference" reason:@"UIKit.framework.Exception" userInfo:nil];
    @throw e;
} 

-(IBAction) crash
{
    [self throwNSException];
}

-(IBAction) register
{
    [UMSAgent postEvent:@"login"  acc:1];
    [UMSAgent postEvent:@"login" label:@"label1" acc:10];
    [UMSAgent postEvent:@"login" label:@"lable2"];
    [UMSAgent postEvent:@"click" acc:99];
    [UMSAgent postEvent:@"quit"];
	[UMSAgent postEvent:@"click" acc:1];
    [UMSAgent postEvent:@"quit" acc:1];
}


-(IBAction) goToSecondView
{
    SecondViewController *secondViewController = [[SecondViewController alloc] init];

    [self presentViewController:secondViewController animated:YES completion:nil];
}

-(IBAction) tag
{
//    [UMSAgent postTag:@"ios tag"];
}

-(void)viewWillAppear:(BOOL)animated
{
    [super viewWillAppear:YES];
    [UMSAgent tracePage:@"Login"];
}

- (void)viewWillDisappear:(BOOL)animated
{
    [super viewWillDisappear:YES];
//    [UMSAgent endTracPage:@"LoginActivity"];
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
