#1 Hướng dẫn viết restful API sử dụng cho thuật toán naive bayes phân loại cảm xúc người dùng sản phẩm công nghệ
#2 Một restful api phải làm 3 việc sau:
-**Nhận các request: hàm _input()**
-**Xử lý các request: hàm _process_api()**
-**Trả về response: hàm response()**

#2 Chúng ta sẽ dần xây dựng cả 3 hàm trên, đầu tiên mình sẽ viết ra cấu trúc căn bản của class restful_api với các hàm và thuộc tính cơ bản:
```sh
class restful_api {
    protected $method   = '';
    protected $endpoint = '';
    protected $params   = array();
    protected $file     = null;


    public function __construct(){
        $this->_input();
        $this->_process_api();
    }

    private function _input(){
        // code của hàm _input
    }

    private function _process_api(){
        // code của hàm _process_api
    }
}
```
