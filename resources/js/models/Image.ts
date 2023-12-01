import Model from '../model'

interface Image {

}

class Image extends Model {
    resource(): string {
        return 'images'
    }
}

export default Image
