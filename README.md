#1. Hướng dẫn viết restful API sử dụng cho thuật toán naive bayes phân loại cảm xúc người dùng sản phẩm công nghệ
#2. Một restful api phải làm 3 việc sau:

-**Nhận các request: hàm _input()**

-**Xử lý các request: hàm _process_api()**

-**Trả về response: hàm response()**

#2. Chúng ta sẽ dần xây dựng cả 3 hàm trên, đầu tiên mình sẽ viết ra cấu trúc căn bản của class restful_api với các hàm và thuộc tính cơ bản:
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
#2 Chúng ta sẽ thực hiện lấy enpoint và các params trong hàm _input()
```sh
class restful_api {

    ...

    private function _input(){
        $this->params = explode('/', trim($_SERVER['PATH_INFO'],'/'));
        $this->endpoint = array_shift($this->params);

        // Lấy method của request
        $method         = $_SERVER['REQUEST_METHOD'];
        $allow_method   = array('GET', 'POST', 'PUT', 'DELETE');

        if (in_array($method, $allow_method)){
            $this->method = $method;
        }

        // Nhận thêm dữ liệu tương ứng theo từng loại method
        switch ($this->method) {
            case 'POST':
                $this->params = $_POST;
            break;

            case 'GET':
                // Không cần nhận, bởi params đã được lấy từ url
            break;

            case 'PUT':
                $this->file    = file_get_contents("php://input");
            break;

            case 'DELETE':
                // Không cần nhận, bởi params đã được lấy từ url
            break;

            default:
                $this->response(500, "Invalid Method");
            break;
        }
    }

    ...

}

```

#2 Khi đã có được endpoint và các dữ liệu cần thiết, chúng ta sẽ gọi hàm endpoint tương ứng
```sh
class restful_api {

    ...

    private function _process_api(){        
        if (method_exists($this, $this->endpoint)){
            $this->{$this->endpoint}();
        }
        else {
            $this->response(500, "Unknown endpoint");
        }
    }

    ...

}

```
#2 Trả về response chỉ cần dùng hàm header của php để trả về http response theo mã http tương ứng.
```sh
class restful_api {

    ...

    /**
     * Trả dữ liệu về client
     * @param: $status_code: mã http trả về
     * @param: $data: dữ liệu trả về
     */
    protected function response($status_code, $data = NULL){
        header($this->_build_http_header_string($status_code));
        header("Content-Type: application/json");
        echo json_encode($data);
        die();
    }
    
    /**
     * Tạo chuỗi http header
     * @param: $status_code: mã http
     * @return: Chuỗi http header, ví dụ: HTTP/1.1 404 Not Found
     */
    private function _build_http_header_string($status_code){
        $status = array(
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error'
        );
        return "HTTP/1.1 " . $status_code . " " . $status[$status_code];
    }

    ...

}

```
