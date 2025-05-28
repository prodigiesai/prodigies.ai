package ai.prodigies.predicta_api.model;

import org.junit.jupiter.api.Test;
import org.springframework.boot.autoconfigure.data.mongo.MongoRepositoriesAutoConfiguration;
import org.springframework.boot.autoconfigure.mongo.MongoAutoConfiguration;
import org.springframework.boot.test.autoconfigure.orm.jpa.DataJpaTest;
import org.springframework.test.context.ActiveProfiles;

import static org.assertj.core.api.Assertions.assertThat;

@DataJpaTest(excludeAutoConfiguration = {MongoAutoConfiguration.class, MongoRepositoriesAutoConfiguration.class})
@ActiveProfiles("test")
public class SalesRecordBuilderTest {

    @Test
    void shouldBuildSalesRecordUsingBuilder() {
        // Arrange
        SalesRecord salesRecord = SalesRecord.builder()
                .id(1L)
                .customerName("John Doe")
                .productName("Product A")
                .quantity(10)
                .price(500.0)
                .build();

        // Assert
        assertThat(salesRecord.getId()).isEqualTo(1L);
        assertThat(salesRecord.getCustomerName()).isEqualTo("John Doe");
        assertThat(salesRecord.getProductName()).isEqualTo("Product A");
        assertThat(salesRecord.getQuantity()).isEqualTo(10);
        assertThat(salesRecord.getPrice()).isEqualTo(500.0);
    }
}