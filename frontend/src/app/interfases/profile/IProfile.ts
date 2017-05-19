export interface IProfile {
  user_image: string;
  user_type: string; // user / artist
  firstname: string;
  lastname: string;
  followers: string; // count followers
  following: string; // count following
  follow_status: boolean;
  followingId: string;
  username: string;
  my_profile: boolean; // if profile my - true, else - false
  user_roles_ar: string[]; // ["Photographer", "Game Developer"]
  loggedin: boolean;
  playlists: Object[];
}