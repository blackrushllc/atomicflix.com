import { Auth, Site, User } from './api-client.js';

/**
 * Example usage of the AtomicFlix API Client
 */
async function testApi() {
    console.log('Testing AtomicFlix API Client...');

    try {
        // Test getting site meta
        console.log('Fetching site meta...');
        const siteMeta = await Site.getMeta();
        console.log('Site Meta Response:', siteMeta);

        // Test login (mock)
        console.log('Attempting mock login...');
        const loginResponse = await Auth.login({
            username: 'testuser',
            password: 'password123'
        });
        console.log('Login Response:', loginResponse);

        // Test getting profile
        console.log('Fetching user profile...');
        const profile = await User.getProfile();
        console.log('Profile Response:', profile);

    } catch (error) {
        console.error('API Test Failed:', error);
    }
}

// Uncomment to run the test if this script is included in a page
// testApi();
