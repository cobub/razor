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
//    [UMSAgent checkUpdate];
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
    [UMSAgent postEvent:@"ios_click_exit"  acc:888];
	[UMSAgent postEvent:@"ios_click" acc:666];
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
