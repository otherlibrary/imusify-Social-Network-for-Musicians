import { environment } from '../environments/environment';

export class AppConfig {

    /* Base URL for API */
    public readonly apiHost = environment.host;

    public readonly apiUrls = {
        login: '/api/authenticate',  
        logout: '/api/logout',
        refreshToken: '/api/refresh',
        activate: '/api/activate',
        createUser: '/api/users',
    }

    /* Gender list */
    public readonly gender = [
        { value: 'male', label: 'Male' },
        { value: 'female', label: 'Female' }
    ]

    /* Error messages */
    public readonly errorMessages = {
        first_name:   'Please, type a first name',
        last_name:    'Please, fill this field',
        username:     'Username is required',
        password:     'Password is required and must be at least 4 characters long.',
        password_len: 'Password must be at least 4 characters long.',
        email:        'Please, check an email',
        gender:       'Choose a gender. This field is a necessary',
        terms:        'Please, read and agree our "Terms of Use Agreement"'
    }
}