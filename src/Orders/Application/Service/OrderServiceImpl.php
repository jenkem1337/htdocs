<?php

class OrderServiceImpl implements OrderService {
    function __construct(
        private OrderRepository $orderRepository,
        private PaymentService $paymentService,
        private ShippingService $shippingService,
        private ProductService $productService,
        private EmailService $emailService
    ){}

    function placeOrder(PlaceOrderDto $placeOrderDto): ResponseViewModel{
        
        $order = Order::placeOrder(
            $placeOrderDto->getUserUuid(),
            $placeOrderDto->getAmount(),
            $placeOrderDto->getOrderItems()
        );

        $this->productService->checkQuantityAndDecrease(new CheckAndDecreaseProductsDto($order->getItems()));

        $shipmentResponse = $this->shippingService->createShippingForOrderCreation(new CreationalShippingDto($order->getAmount(), $placeOrderDto->getAddressTitle(),$placeOrderDto->getAddressOwnerName(),$placeOrderDto->getAddressOwnerSurname(),$placeOrderDto->getFullAddress(),$placeOrderDto->getAddressCountry(),$placeOrderDto->getAddressProvince(),$placeOrderDto->getAddressDistrict(),$placeOrderDto->getAddressZipCode()))
                                            ->getData();
        $order->determineLatestAmount($shipmentResponse["price"]);
        
        $paymentResponse = $this->paymentService->pay(new PayOrderDto($placeOrderDto->getUserUuid(), $placeOrderDto->getPaymentMethod(), $placeOrderDto->getPaymentDetail(), $order->getAmount()))
                                        ->getData();
        
        
        $order->setStateToProcessingAndAssignPaymentAndShippingUuid($paymentResponse["uuid"], $shipmentResponse["uuid"]);
        
        $this->orderRepository->saveChanges($order);
        
        //$this->emailService->notifyUserForOrderCreated($placeOrderDto);
        return new SuccessResponse([
            "message" => "Order created successfully !!",
            "data" => [
                "order_uuid" => $order->getUuid(),
            ]
        ]);
    }

    function findByCriteria() {}
    
    //TODO This method is in progress.
    function cancelOrder(OrderStatusDto $dto):ResponseViewModel{
        $orderAggregate = $this->orderRepository->findOneAggregateByUuid($dto->getUuid());

        if($orderAggregate->isNull()) throw new NotFoundException("order");

        $this->paymentService->refund(new RefundPaymentDto($orderAggregate->getPaymentUuid()));

        $this->shippingService->setStateToCanceled(new ShippingStatusDto($orderAggregate->getShippingUuid()));


        return new SuccessResponse([]);
    }
    
    function orderDelivered(OrderStatusDto $dto): ResponseViewModel{
        $orderAggregate = $this->orderRepository->findOneAggregateByUuid($dto->getUuid());
        
        if($orderAggregate->isNull()) throw new NotFoundException("order");
        
        $this->shippingService->isShipmentDelivered(new IsShippingDeliveredDto($orderAggregate->getShippingUuid()));

        $orderAggregate->setStatusToDelivered();
        
        $this->orderRepository->saveChanges($orderAggregate);
        
        return new SuccessResponse([
            "message" => "Order successfully delivered.",
            "data" => [
                "uuid" => $orderAggregate->getUuid()
            ]
        ]);

    }
    function shipOrder(OrderStatusDto $dto):ResponseViewModel{
        $orderAggregate = $this->orderRepository->findOneAggregateByUuid($dto->getUuid());
        
        if($orderAggregate->isNull()) throw new NotFoundException("order");
        
        $this->shippingService->setStateToDispatched(new ShippingStatusDto($orderAggregate->getShippingUuid()));
        
        $orderAggregate->setStatusToDispatched();
        
        $this->orderRepository->saveChanges($orderAggregate);
        
        return new SuccessResponse([
            "message" => "Order successfully dispatched.",
            "data" => [
                "uuid" => $orderAggregate->getUuid()
            ]
        ]);
    }
    function refundOrder(OrderStatusDto $dto){}
    
}