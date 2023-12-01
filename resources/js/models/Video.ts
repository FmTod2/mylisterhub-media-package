import Model from '../model'

interface Video {

}

class Video extends Model {
    resource(): string {
        return 'videos'
    }
}

export default Video
